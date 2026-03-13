<?php
session_start();
include 'config.php';
date_default_timezone_set('UTC');

header('Content-Type: application/json');

function resp(bool $ok, array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok], $extra));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resp(false, ['message' => 'Método no permitido.']);
}

$token    = trim($_POST['token']    ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm_password'] ?? $password); // compatibilidad

if (!$token || !$password) {
    resp(false, ['message' => 'Datos incompletos.']);
}

if (strlen($password) < 8) {
    resp(false, ['message' => 'La contraseña debe tener al menos 8 caracteres.']);
}

if ($password !== $confirm) {
    resp(false, ['message' => 'Las contraseñas no coinciden.']);
}

// Verificar que el token exista y no haya expirado
$stmt = $pdo->prepare("SELECT id FROM suscripciones WHERE token = ? AND expira_token > NOW() LIMIT 1");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    resp(false, ['message' => 'El enlace ha expirado o es inválido. Solicita uno nuevo.']);
}

// Actualizar contraseña y limpiar token/OTP
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare(
    "UPDATE suscripciones
     SET password_hash = ?, token = NULL, expira_token = NULL, codigo_recuperacion = NULL
     WHERE id = ?"
);
$stmt->execute([$hash, $user['id']]);

resp(true, ['message' => 'Contraseña actualizada con éxito.']);