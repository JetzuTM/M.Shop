<?php
session_start();
require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idpedido'])) {
    $idpedido = $_POST['idpedido'];

    // Primero, obtenemos los detalles del pedido
    $sql_detalles = "SELECT idproducto, cantidad FROM detalles_pedido WHERE idpedido = $idpedido";
    $resultado_detalles = mysqli_query($conexion, $sql_detalles);

    if (mysqli_num_rows($resultado_detalles) > 0) {
        // Recorremos los detalles del pedido y actualizamos el stock
        while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
            $idproducto = $detalle['idproducto'];
            $cantidad = $detalle['cantidad'];

            // Devolvemos la cantidad al stock sumándola al stock actual
            $sql_stock = "UPDATE productos SET stock = stock + $cantidad WHERE idproducto = $idproducto";
            if (!mysqli_query($conexion, $sql_stock)) {
                echo "Error al devolver el stock del producto con ID $idproducto: " . mysqli_error($conexion);
                exit();
            }
        }

        // Si todo sale bien, retornamos OK
        echo "OK";
    } else {
        echo "No se encontraron detalles del pedido.";
    }
} else {
    echo "ID de pedido no válido.";
}
?>