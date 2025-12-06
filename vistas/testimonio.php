<?php
require_once "../config/conexion.php";

if ($_POST) {
    $idpedido = $_POST['idpedido'];
    $estado = $_POST['estado'];

    $sql = "UPDATE pedidos SET entrega = '$estado' WHERE idpedido = '$idpedido'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado) {
        echo "Estado de seguimiento actualizado correctamente";
    } else {
        echo "Error al actualizar el estado de seguimiento";
    }
}
?>