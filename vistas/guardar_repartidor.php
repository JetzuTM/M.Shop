<?php
session_start();
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

// Incluir la configuración de la base de datos
require_once "../config/conexion.php"; // Asegúrate de que la ruta sea correcta

// Verificar si el pedido en curso está en la sesión
if (!isset($_SESSION['id_pedido_en_curso'])) {
    echo json_encode(['success' => false, 'message' => 'No se encontró un pedido en curso. Por favor, realice la compra nuevamente.']);
    exit;
}

// Obtener el ID del repartidor enviado en la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$id_delivery = $data['id_delivery'] ?? null;  // Cambiado a "id_delivery" para coincidir

// Verificar si se ha enviado un ID de repartidor
if (!$id_delivery) {
    echo json_encode(['success' => false, 'message' => 'No se seleccionó un repartidor.']);
    exit;
}

// Obtener el ID del pedido en curso
$id_pedido = $_SESSION['id_pedido_en_curso'];

// Conexión a la base de datos ya definida en conexion.php
if ($conexion) {
    // Actualizar el pedido con el repartidor seleccionado
    $sql = "UPDATE pedidos SET id_delivery = ? WHERE idpedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_delivery, $id_pedido);

    // Ejecutar la consulta y devolver la respuesta adecuada
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Repartidor asignado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al asignar el repartidor.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
}

$conexion->close();
?>
