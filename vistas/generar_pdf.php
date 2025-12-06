<?php
// Iniciamos las variables de sesión
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit;  // Salimos para evitar ejecución adicional
}

// Verificamos si el usuario tiene permisos para el almacén
if ($_SESSION['almacen'] != 1) {
    require 'noacceso.php';  // Incluimos la página de acceso denegado
    exit;  // Salimos después de incluir el archivo
}

// Ahora continuamos con el resto del código original
require_once '../config/conexion.php';   // Conexión a la base de datos
require_once '../fpdf181/fpdf.php';      // Librería FPDF

// ---------------------------------------------------------------------------
// Datos fijos de la empresa (puedes moverlos a un archivo de configuración)
$ext_logo  = "png";             // extensión del logo
$empresa   = "MANFRA C.A";
$documento = "J-123456789";
$direccion = "Magnifica C.A";
$telefono  = "04120444652";
$email     = "Email: manfraca@gmail.com";
// ---------------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {

    $pedidoId = intval($_POST['pedido_id']);

    // Consulta del pedido y sus detalles, modificada para incluir el nombre del usuario
    $sql = "SELECT p.idpedido, p.fecha, p.estado, p.entrega, p.referencia_pago,
                   dp.cantidad, dp.precio,
                   a.nombre AS nombre_articulo,
                   d.nombre AS nombre_delivery,
                   s.nombre AS nombre_usuario  
            FROM pedidos p
            JOIN detalle_pedido dp ON p.idpedido = dp.idpedido
            JOIN articulo a ON dp.idarticulo = a.idarticulo
            LEFT JOIN delivery d ON p.id_delivery = d.iddelivery
            JOIN suscripciones s ON p.idusuario = s.id  
            WHERE p.idpedido = ?";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pedidoId);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // Verificar si el pedido existe
    if (mysqli_num_rows($resultado) > 0) {

        // -------------------------------------------------------------------
        // Armamos el arreglo $pedido con todos los datos, incluyendo el nombre del usuario
        // -------------------------------------------------------------------
        $pedido = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            if (empty($pedido)) {
                $pedido = [
                    'idpedido'        => $row['idpedido'],
                    'fecha'           => $row['fecha'],
                    'estado'          => $row['estado'],
                    'entrega'         => $row['entrega'],
                    'referencia_pago' => $row['referencia_pago'],
                    'nombre_delivery' => $row['nombre_delivery'] ?? 'No asignado',
                    'nombre_usuario'  => $row['nombre_usuario'],  
                    'articulos'       => []
                ];
            }
            $pedido['articulos'][] = [
                'nombre'   => $row['nombre_articulo'],
                'cantidad' => $row['cantidad'],
                'precio'   => $row['precio']
            ];
        }

        // -------------------------------------------------------------------
        // Creación del PDF
        // -------------------------------------------------------------------
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Cuadro de Fecha
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(150, 24); // debajo del cuadro anterior
        $pdf->Cell(50, 7, utf8_decode('N° DE REPORTE'), 1, 2, 'C'); // primer cuadro vacío
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 7, '' . $pedido['idpedido'], 1, 2, 'C'); // espacio para escribir

        // Cuadro de N° de Reporte (esquina superior derecha)
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(150, 10); // X = 150 mm, Y = 10 mm desde arriba
        $pdf->Cell(50, 7, 'FECHA', 1, 2, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 7, '' . date('d/m/Y', strtotime($pedido['fecha'])), 1, 2, 'C'); // espacio para escribir la fecha

        
        /* ****************** ENCABEZADO CON LOGO + DATOS ******************* */

        // Logo (10 mm desde el borde izquierdo, 10 mm de alto)  
        $pdf->Image('../public/img/Multi.' . $ext_logo, 10, 10, 30);

        // Nos movemos a la derecha del logo: X = 10 + 30 + 5 = 45
        $pdf->SetXY(45, 10);

        // Nombre de la empresa
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 6, $empresa, 0, 1);

        // Resto de la información
        $pdf->SetFont('Arial', '', 10);

        $pdf->SetX(45);
        $pdf->Cell(0, 5, "$documento", 0, 1);

        $pdf->SetX(45);
        $pdf->Cell(0, 5, utf8_decode("Dirección: $direccion"), 0, 1);

        $pdf->SetX(45);
        $pdf->Cell(0, 5, utf8_decode("Teléfono: $telefono"), 0, 1);

        $pdf->SetX(45);
        $pdf->Cell(0, 5, $email, 0, 1);

        // Espacio debajo de la cabecera
        $pdf->Ln(8);


        /* ****************** INFORMACIÓN DEL PEDIDO ******************* */
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, 'Cliente: ' . $pedido['nombre_usuario'], 0, 1);  // Modificado: Usa el nombre del usuario
        $pdf->Cell(0, 6, 'Estado: ' . $pedido['estado'], 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Entrega: ') . ($pedido['entrega'] ? utf8_decode('Sí') : utf8_decode('No')), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Repartidor: ') . $pedido['nombre_delivery'], 0, 1);
        $pdf->Cell(0, 6, 'Referencia de Pago: ' . ($pedido['referencia_pago'] ?: 'No disponible'), 0, 1);
        $pdf->Ln(8);

        /* ****************** TABLA DE DETALLES ******************* */
        // Encabezados
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $w = [60, 30, 40, 40];                // Anchos de columna

        $pdf->Cell($w[0], 7, utf8_decode('Artículo'),        1, 0, 'C', 1);
        $pdf->Cell($w[1], 7, 'Cantidad',        1, 0, 'C', 1);
        $pdf->Cell($w[2], 7, 'Precio Unitario', 1, 0, 'C', 1);
        $pdf->Cell($w[3], 7, 'Subtotal',        1, 1, 'C', 1);

        // Filas
        $pdf->SetFont('Arial', '', 10);
        $total = 0;
        foreach ($pedido['articulos'] as $art) {
            $subtotal = $art['cantidad'] * $art['precio'];
            $total   += $subtotal;

            $pdf->Cell($w[0], 6, $art['nombre'],                          1);
            $pdf->Cell($w[1], 6, $art['cantidad'],                        1, 0, 'C');
            $pdf->Cell($w[2], 6, 'Bs ' . number_format($art['precio'],2), 1, 0, 'C');
            $pdf->Cell($w[3], 6, 'Bs ' . number_format($subtotal,2),      1, 1, 'R');
        }

        /* ****************** TOTAL ******************* */
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($w[0] + $w[1] + $w[2], 8, 'TOTAL', 1, 0, 'R');
        $pdf->Cell($w[3], 8, 'Bs ' . number_format($total, 2), 1, 1, 'R');

        /* ****************** SALIDA ******************* */
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte_pedido_' . $pedido['idpedido'] . '.pdf"');
        $pdf->Output();

    } else {
        echo "Pedido no encontrado.";
    }
} else {
    echo "Solicitud inválida.";
}
?>