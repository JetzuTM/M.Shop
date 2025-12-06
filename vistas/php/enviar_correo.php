<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
include 'config.php';

// Establecer la zona horaria a UTC
date_default_timezone_set('UTC');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Verificar si el email existe en la base de datos
    $stmt = $pdo->prepare("SELECT id FROM suscripciones WHERE correo = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generar un token único
        $token = bin2hex(random_bytes(32));
        
        // Calcular la fecha de expiración en UTC (1 hora desde el momento actual)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Mostrar la fecha y hora actuales en UTC, y la fecha de expiración para depuración
        echo "Hora actual (UTC): " . date('Y-m-d H:i:s') . "<br>";
        echo "Fecha de expiración (UTC): " . $expires . "<br>";
        echo "Token generado: " . $token . "<br>";
        
        // Almacenar el token y la fecha de expiración en la base de datos
        $stmt = $pdo->prepare("UPDATE suscripciones SET token = ?, expira_token = ? WHERE correo = ?");
        $stmt->execute([$token, $expires, $email]);

        // Envío del correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Yostynmedina550@gmail.com'; // Tu correo SMTP
            $mail->Password = 'wmis lptw yoof jicf'; // Tu contraseña o contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('no-reply@tu-dominio.com', 'Recuperación de Contraseña');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recupera tu contraseña';
            $mail->Body = 'Haz clic en el siguiente enlace para recuperar tu contraseña: <a href="http://localhost/MultiShop/vistas/php/recuperar_contraseña.php?token=' . $token . '">Recuperar Contraseña</a>';

            // Habilitar depuración de SMTP (nivel detallado)
            $mail->SMTPDebug = 3;

            // Enviar el correo
            $mail->send();
            echo 'Correo de recuperación enviado con éxito';
        } catch (Exception $e) {
            echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'No se encontró una cuenta con ese correo electrónico';
    }
}
?>