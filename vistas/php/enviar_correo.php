<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
include 'config.php';

date_default_timezone_set('UTC');

header('Content-Type: application/json');

// ── Utilidad: respuesta JSON ──────────────────────────────────────────────────
function resp(bool $ok, array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok], $extra));
    exit;
}

// ── Valida entrada ────────────────────────────────────────────────────────────
if (!isset($_POST['metodo'], $_POST['valor'])) {
    resp(false, ['message' => 'Datos incompletos.']);
}

$metodo = trim($_POST['metodo']);
$valor  = trim($_POST['valor']);

if (!in_array($metodo, ['email','cedula','telefono']) || $valor === '') {
    resp(false, ['message' => 'Parámetros inválidos.']);
}

// ── Buscar usuario según el método ───────────────────────────────────────────
$columna = match($metodo) {
    'email'    => 'correo',
    'cedula'   => 'cedula',
    'telefono' => 'telefono',
};

$stmt = $pdo->prepare("SELECT id, nombre, correo FROM suscripciones WHERE {$columna} = ? LIMIT 1");
$stmt->execute([$valor]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    resp(false, ['message' => 'No se encontró ninguna cuenta con esos datos.']);
}

// ── Generar OTP de 6 dígitos ──────────────────────────────────────────────────
$otp     = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

$stmt = $pdo->prepare("UPDATE suscripciones SET codigo_recuperacion = ?, expira_token = ? WHERE id = ?");
$stmt->execute([$otp, $expires, $user['id']]);

// ── Enmascarar correo para el cliente ────────────────────────────────────────
$correo = $user['correo'];
$partes = explode('@', $correo);
$mask   = substr($partes[0], 0, 2) . str_repeat('*', max(2, strlen($partes[0]) - 2)) . '@' . $partes[1];

// ── Plantilla del correo ──────────────────────────────────────────────────────
$nombre = htmlspecialchars($user['nombre']);
$cuerpo = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Código de Recuperación — MultiShop</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="520" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.1);">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#1a1a2e,#0f3460);padding:36px 40px;text-align:center;">
            <div style="font-size:36px;margin-bottom:10px;">🔐</div>
            <h1 style="color:#fff;font-size:22px;margin:0;font-weight:700;">Recuperación de Cuenta</h1>
            <p style="color:rgba(255,255,255,0.7);margin:6px 0 0;font-size:13px;">MultiShop — Sistema de seguridad</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:36px 40px;">
            <p style="font-size:15px;color:#444;margin:0 0 8px;">Hola, <strong style="color:#1a1a2e;">{$nombre}</strong> 👋</p>
            <p style="font-size:14px;color:#666;line-height:1.6;margin:0 0 28px;">
              Recibimos una solicitud para recuperar el acceso a tu cuenta. Usa el siguiente código de verificación para continuar.</p>

            <!-- OTP Box -->
            <div style="text-align:center;margin:0 0 28px;">
              <div style="display:inline-block;background:linear-gradient(135deg,#f0f4ff,#e8edff);border:2px solid #c7d2fe;border-radius:16px;padding:20px 40px;">
                <p style="margin:0 0 4px;font-size:11px;color:#6366f1;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Tu código de verificación</p>
                <p style="margin:0;font-size:42px;font-weight:800;color:#1a1a2e;letter-spacing:14px;font-family:'Courier New',monospace;">{$otp}</p>
              </div>
            </div>

            <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:24px;">
              <p style="margin:0;font-size:13px;color:#92400e;">
                ⚠️ <strong>Este código expira en 15 minutos.</strong> Si no solicitaste este cambio, ignora este mensaje; tu cuenta está segura.
              </p>
            </div>

            <p style="font-size:13px;color:#999;margin:0;">Por tu seguridad, nunca compartas este código con nadie.</p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f8f9fc;padding:20px 40px;text-align:center;border-top:1px solid #eee;">
            <p style="margin:0;font-size:12px;color:#bbb;">© 2025 MultiShop · Todos los derechos reservados</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;

// ── Enviar correo con PHPMailer ───────────────────────────────────────────────
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'josberhernandez1@gmail.com';
    $mail->Password   = 'ajoz xpfq szvq gvnd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPDebug  = 0; // Sin salida de depuración

    $mail->setFrom('no-reply@multishop.com', 'MultiShop Seguridad');
    $mail->addAddress($correo, $user['nombre']);
    $mail->isHTML(true);
    $mail->Subject = '🔐 Tu código de verificación — MultiShop';
    $mail->Body    = $cuerpo;
    $mail->AltBody = "Tu código de recuperación de contraseña es: {$otp}\nExpira en 15 minutos.";

    $mail->send();
    resp(true, ['correo_masked' => $mask]);

} catch (Exception $e) {
    resp(false, ['message' => 'No se pudo enviar el correo. Inténtalo más tarde.']);
}