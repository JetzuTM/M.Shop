<?php
require_once "../config/conexion.php";

$idpedido = $_POST['idpedido'];
$estado = $_POST['estado'];

// Función para actualizar el estado
function actualizarEstadoPedido($conexion, $idPedido, $nuevoEstado){
    $sql = "UPDATE pedidos SET estado = ? WHERE idpedido = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nuevoEstado, $idPedido);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt) > 0;
}

// Obtener el estado anterior del pedido
$sql_estado_anterior = "SELECT estado FROM pedidos WHERE idpedido = $idpedido";
$resultado_estado_anterior = mysqli_query($conexion, $sql_estado_anterior);
$row_estado_anterior = mysqli_fetch_assoc($resultado_estado_anterior);
$estado_anterior = $row_estado_anterior['estado'];

// Manejo del stock según el nuevo estado
if ($estado == 'Rechazado') {
    // Obtener los detalles del pedido para revertir el stock
    $sql_detalles = "SELECT dp.idarticulo, dp.cantidad FROM detalle_pedido dp WHERE dp.idpedido = $idpedido";
    $resultado_detalles = mysqli_query($conexion, $sql_detalles);

    while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
        $idarticulo = $detalle['idarticulo'];
        $cantidad = $detalle['cantidad'];

        // Actualizar el stock del artículo
        $sql_update_stock = "UPDATE articulo SET stock = stock + $cantidad WHERE idarticulo = $idarticulo";
        $resultado_update_stock = mysqli_query($conexion, $sql_update_stock);

        if (!$resultado_update_stock) {
            echo "Error al actualizar el stock del artículo $idarticulo: " . mysqli_error($conexion);
            exit; // Detener la ejecución si hay un error al actualizar el stock
        }
    }

    $mensaje = "Tu pedido N$idpedido ha sido RECHAZADO.";
} elseif (($estado == 'Aceptado' || $estado == 'Finalizado') && $estado_anterior == 'Rechazado') {
    // Si el pedido había sido rechazado anteriormente y ahora se acepta o finaliza, se debe restar el stock nuevamente
    $sql_detalles = "SELECT dp.idarticulo, dp.cantidad FROM detalle_pedido dp WHERE dp.idpedido = $idpedido";
    $resultado_detalles = mysqli_query($conexion, $sql_detalles);

    while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
        $idarticulo = $detalle['idarticulo'];
        $cantidad = $detalle['cantidad'];

        // Actualizar el stock del artículo
        $sql_update_stock = "UPDATE articulo SET stock = stock - $cantidad WHERE idarticulo = $idarticulo";
        $resultado_update_stock = mysqli_query($conexion, $sql_update_stock);

        if (!$resultado_update_stock) {
            echo "Error al actualizar el stock del artículo $idarticulo: " . mysqli_error($conexion);
            exit; // Detener la ejecución si hay un error al actualizar el stock
        }
    }
}
elseif (($estado == 'Finalizado') && ($estado_anterior != 'Aceptado' && $estado_anterior != 'Finalizado' && $estado_anterior != 'Rechazado')){
    // Si el estado actual es finalizado y el anterior no es ni aceptado ni finalizado ni rechazado
     $sql_detalles = "SELECT dp.idarticulo, dp.cantidad FROM detalle_pedido dp WHERE dp.idpedido = $idpedido";
        $resultado_detalles = mysqli_query($conexion, $sql_detalles);

        while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
            $idarticulo = $detalle['idarticulo'];
            $cantidad = $detalle['cantidad'];

            // Actualizar el stock del artículo
            $sql_update_stock = "UPDATE articulo SET stock = stock - $cantidad WHERE idarticulo = $idarticulo";
            $resultado_update_stock = mysqli_query($conexion, $sql_update_stock);

            if (!$resultado_update_stock) {
                echo "Error al actualizar el stock del artículo $idarticulo: " . mysqli_error($conexion);
                exit; // Detener la ejecución si hay un error al actualizar el stock
            }
        }
}
// Actualizar el estado del pedido
$sql = "UPDATE pedidos SET estado = '$estado' WHERE idpedido = $idpedido";
// Después de actualizar el estado exitosamente, añade:
if (mysqli_query($conexion, $sql)) {
    if ($estado == 'Rechazado') {
        $mensaje = "Tu pedido ha sido RECHAZADO.";
    } else {
        $mensaje = "Tu pedido ha sido ACEPTADO.";
    }
    $sql_notificacion = "INSERT INTO notificaciones (idusuario, mensaje, fecha, leido) 
                         SELECT idusuario, ?, NOW(), 0 
                         FROM pedidos WHERE idpedido = ?";
    $stmt = mysqli_prepare($conexion, $sql_notificacion);
    mysqli_stmt_bind_param($stmt, "si", $mensaje, $idpedido);
    mysqli_stmt_execute($stmt);
    
    echo "Estado del pedido actualizado con éxito";
} else {
    echo "Error al actualizar el estado del pedido: " . mysqli_error($conexion);
}
?>