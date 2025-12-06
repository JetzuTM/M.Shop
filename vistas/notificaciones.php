<?php
session_start();
require_once "../config/conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["idusuario"]) && !isset($_SESSION["correo"])) {
    header("Location: escritorio_tienda.php");
    exit();
}

$idUsuario = $_SESSION["idusuario"] ?? $_SESSION["correo"];
// Verificar si se debe marcar todas las notificaciones como leídas
if (isset($_POST['marcar_todas_como_leidas'])) {
    $sql = "UPDATE notificaciones SET leido = 1";
    if (mysqli_query($conexion, $sql)) {
        $response = ['status' => 'success', 'message' => 'Todas las notificaciones han sido marcadas como leídas.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error al marcar las notificaciones como leídas: ' . mysqli_error($conexion)];
    }
} else {
    // Obtener el mensaje de la notificación no leída más reciente
    $sql = "SELECT mensaje FROM notificaciones WHERE leido = 0 ORDER BY fecha DESC LIMIT 1";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        $mensaje = $fila['mensaje'];
        $notificaciones = 1;
    } else {
        $mensaje = '';
        $notificaciones = 0;
    }

    $response = ['status' => 'success', 'notificaciones' => $notificaciones, 'mensaje' => $mensaje];
}

echo json_encode($response);
?>