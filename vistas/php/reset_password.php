<?php

// reset_password.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../config/conexion.php';
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar el token y su validez
    $query = $pdo->prepare("SELECT * FROM usuarios WHERE token = :token AND expira_token > NOW()");
    $query->execute(['token' => $token]);
    $user = $query->fetch();

    if ($user) {
        // Actualizar la contraseña
        $updatePassword = $pdo->prepare("UPDATE usuarios SET clave = :clave, token = NULL, expira_token = NULL WHERE token = :token");
        $updatePassword->execute(['clave' => $newPassword, 'token' => $token]);

        echo "Tu contraseña ha sido actualizada.";
    } else {
        echo "El token es inválido o ha expirado.";
    }
}
?>