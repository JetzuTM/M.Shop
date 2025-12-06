<?php
require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idpedido'])) {
    $idpedido = $_POST['idpedido'];
    
    $sql = "SELECT dp.cantidad, dp.precio, a.nombre as nombre_articulo, a.imagen
            FROM detalle_pedido dp
            JOIN articulo a ON dp.idarticulo = a.idarticulo
            WHERE dp.idpedido = ?";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idpedido);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    $detalles = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $detalles[] = $row;
    }
    
    echo json_encode($detalles);
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Solicitud no válida']);
}
?>
