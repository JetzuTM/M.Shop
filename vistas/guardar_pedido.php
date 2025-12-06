<?php
// Desactivar la salida de errores PHP al navegador
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Función para registrar errores en un archivo de log
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, '../logs/error.log');
}

// Asegurarse de que la respuesta sea siempre JSON
header('Content-Type: application/json');

try {
    session_start();
    require_once "../config/conexion.php";

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION["idusuario"]) && !isset($_SESSION["correo"])) {
        throw new Exception("Usuario no autenticado");
    }

    $idUsuario = $_SESSION["idusuario"] ?? $_SESSION["correo"];
    
    $input = file_get_contents('php://input');
    $orderData = json_decode($input, true);

    if (!$orderData || empty($orderData['cartItems'])) {
        throw new Exception("Datos de pedido no válidos");
    }

    mysqli_begin_transaction($conexion);

    $estado = "Pendiente";
    $entrega = $orderData['delivery'] ? 1 : 0;
    $fecha = date('Y-m-d H:i:s');
    
    // Obtener la referencia de pago de los detalles de pago
    $referenciaPago = isset($orderData['paymentDetails']['reference']) ? $orderData['paymentDetails']['reference'] : null;

    // Insertar en la tabla principal de pedidos, incluyendo la referencia de pago
    $sqlPedidoPrincipal = "INSERT INTO pedidos (idusuario, fecha, estado, entrega, referencia_pago) VALUES (?, ?, ?, ?, ?)";
    $stmtPedidoPrincipal = mysqli_prepare($conexion, $sqlPedidoPrincipal);
    mysqli_stmt_bind_param($stmtPedidoPrincipal, "sssis", $idUsuario, $fecha, $estado, $entrega, $referenciaPago);
    
    if (!mysqli_stmt_execute($stmtPedidoPrincipal)) {
        throw new Exception("Error al insertar pedido principal: " . mysqli_error($conexion));
    }
    
    $idPedido = mysqli_insert_id($conexion);

    // Insertar los detalles del pedido y actualizar el stock
    $sqlDetallePedido = "INSERT INTO detalle_pedido (idpedido, idarticulo, cantidad, precio) VALUES (?, ?, ?, ?)";
    $stmtDetallePedido = mysqli_prepare($conexion, $sqlDetallePedido);

    foreach ($orderData['cartItems'] as $item) {
        // Actualizar el stock en la tabla articulo
        $sqlActualizarStock = "UPDATE articulo SET stock = stock - ? WHERE idarticulo = ?";
        $stmtActualizarStock = mysqli_prepare($conexion, $sqlActualizarStock);
        mysqli_stmt_bind_param($stmtActualizarStock, "ii", $item['cantidad'], $item['idarticulo']);
        
        if (!mysqli_stmt_execute($stmtActualizarStock)) {
            throw new Exception("Error al actualizar stock: " . mysqli_error($conexion));
        }
        
        mysqli_stmt_bind_param($stmtDetallePedido, "iiid", $idPedido, $item['idarticulo'], $item['cantidad'], $item['precio']);
        
        if (!mysqli_stmt_execute($stmtDetallePedido)) {
            throw new Exception("Error al insertar detalle de pedido: " . mysqli_error($conexion));
        }
    }

    // Si se seleccionó entrega, guardar el ID del pedido en la sesión para asignar el repartidor más adelante
    if ($entrega) {
        $_SESSION['id_pedido_en_curso'] = $idPedido;
    }

    mysqli_commit($conexion);
    echo json_encode(["success" => true, "message" => "Pedido guardado con éxito"]);

} catch (Exception $e) {
    mysqli_rollback($conexion);
    logError($e->getMessage());
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    if (isset($conexion)) {
        mysqli_close($conexion);
    }
}
?>