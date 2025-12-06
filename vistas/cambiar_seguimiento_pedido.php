<?php
require_once "../config/conexion.php";

$idpedido = $_POST['idpedido'];
$estado = $_POST['estado'];

// Obtener el estado anterior del seguimiento
$sql_estado_anterior = "SELECT entrega FROM pedidos WHERE idpedido = $idpedido";
$resultado_estado_anterior = mysqli_query($conexion, $sql_estado_anterior);
$row_estado_anterior = mysqli_fetch_assoc($resultado_estado_anterior);
$estado_anterior = $row_estado_anterior['entrega'];

// Manejo del seguimiento según el nuevo estado
if ($estado == 'En Almacén') {
    $mensaje = "Tu pedido N$idpedido se encuentra EN ALMACÉN.";
} elseif ($estado == 'En Camino') {
    $mensaje = "Tu pedido N$idpedido se encuentra EN CAMINO.";
} elseif ($estado == 'Entregado') {
    $mensaje = "Tu pedido N$idpedido ha sido ENTREGADO.";
}

// Actualizar el estado del seguimiento
$sql = "UPDATE pedidos SET entrega = '$estado' WHERE idpedido = $idpedido";
// Después de actualizar el estado exitosamente, añade:
if (mysqli_query($conexion, $sql)) {
    $sql_notificacion = "INSERT INTO notificaciones (idusuario, mensaje, fecha, leido) 
                         SELECT idusuario, ?, NOW(), 0 
                         FROM pedidos WHERE idpedido = ?";
    $stmt = mysqli_prepare($conexion, $sql_notificacion);
    mysqli_stmt_bind_param($stmt, "si", $mensaje, $idpedido);
    mysqli_stmt_execute($stmt);
    
    echo "Estado del seguimiento actualizado con éxito";
} else {
    echo "Error al actualizar el estado del seguimiento: " . mysqli_error($conexion);
}
?>