<?php
session_start();
include 'config.php';

// Establecer la zona horaria a UTC
date_default_timezone_set('UTC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password_hash']; // Asegúrate de validar y encriptar la nueva contraseña

    // Actualiza la contraseña en la base de datos
    $stmt = $pdo->prepare("UPDATE suscripciones SET password_hash = ?, token = NULL, expira_token = NULL WHERE token = ?");
    $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $token]);

    // Establecer mensaje de éxito en la sesión
    $_SESSION['success_message'] = 'La contraseña se ha cambiado con éxito. Redirigiendo a la página de inicio...';

    // Redirigir de nuevo a la página de restablecimiento para mostrar el mensaje de éxito
    header('Location: ./recuperar_contraseña.php?token=' . $token);
    exit();
}
?>