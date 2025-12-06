<?php
include 'config.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Verificar si el email existe en la base de datos
    $stmt = $pdo->prepare("SELECT id FROM suscripciones WHERE correo = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generar un token único
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira en 1 hora

        // Almacenar el token y la fecha de expiración en la base de datos
        $stmt = $pdo->prepare("UPDATE suscripciones SET token = ?, expira_token = ? WHERE correo = ?");
        $stmt->execute([$token, $expires, $email]);

        // Incluir el archivo para enviar el correo
        include 'enviar_correo.php';
    } else {
        echo 'No se encontró una cuenta con ese correo electrónico';
    }
}
?>