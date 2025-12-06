<?php
session_start(); // Iniciar sesión

include("../config/conexion.php"); // Conexión a la base de datos
// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['name'];
$correo = $_POST['email'];
$telefono = $_POST['phone'];
$cedula = $_POST['cedula'];
$password = $_POST['password'];

// Encriptar la contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Preparar y ejecutar la consulta SQL
$sql = "INSERT INTO suscripciones (nombre, correo, telefono, cedula, password_hash) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssss", $nombre, $correo, $telefono, $cedula, $password_hash);

if ($stmt->execute()) {
    // Si la consulta es exitosa, crear un mensaje de éxito
    $_SESSION['mensaje_exito'] = "¡Te has suscrito con éxito!";
    // Redirigir a la página de éxito
    header("Location: ../vistas/inicio.php");
    exit(); // Asegurarse de que el script termine aquí
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
