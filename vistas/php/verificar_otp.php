<?php
include 'config.php';
date_default_timezone_set('UTC');

header('Content-Type: application/json');

function resp(bool $ok, array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok], $extra));
    exit;
}

if (!isset($_POST['otp'], $_POST['metodo'], $_POST['valor'])) {
    resp(false, ['message' => 'Datos incompletos.']);
}

$otp    = trim($_POST['otp']);
$metodo = trim($_POST['metodo']);
$valor  = trim($_POST['valor']);

if (!preg_match('/^\d{6}$/', $otp)) {
    resp(false, ['message' => 'El código debe tener exactamente 6 dígitos.']);
}

if (!in_array($metodo, ['email','cedula','telefono'])) {
    resp(false, ['message' => 'Método inválido.']);
}

$columna = match($metodo) {
    'email'    => 'correo',
    'cedula'   => 'cedula',
    'telefono' => 'telefono',
};

// Buscar usuario con el OTP + columna correcta + que aún no haya expirado
$stmt = $pdo->prepare(
    "SELECT id FROM suscripciones
     WHERE {$columna} = ?
       AND codigo_recuperacion = ?
       AND expira_token > NOW()
     LIMIT 1"
);
$stmt->execute([$valor, $otp]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    resp(false, ['message' => 'Código incorrecto o expirado. Por favor solicita uno nuevo.']);
}

// ── OTP válido: generar token seguro de sesión para el formulario de contraseña
$token   = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Guardar token en la columna existente, limpiar el OTP
$stmt = $pdo->prepare(
    "UPDATE suscripciones
     SET token = ?, expira_token = ?, codigo_recuperacion = NULL
     WHERE id = ?"
);
$stmt->execute([$token, $expires, $user['id']]);

resp(true, ['token' => $token]);
