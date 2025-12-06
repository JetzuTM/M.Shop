<?php
// Activación de almacenamiento en buffer
ob_start();

// Iniciar sesión si no está iniciada
if (strlen(session_id()) < 1) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    // Verificar permisos de acceso
    if ($_SESSION['almacen'] == 1) {
        require 'PDF_MC_Table.php';

        // Instanciar la clase PDF
        $pdf = new PDF_MC_Table();
        $pdf->AddPage();

        // Agregar el logo con verificación
        if (file_exists('../public/img/Multi.png')) {
            $pdf->Image('../public/img/Multi.png', 10, 10, 30);  // Logo en (10, 10) con ancho 30 mm
        } else {
            $pdf->Cell(0, 10, 'Logo no encontrado', 0, 1);  // Mensaje de error si no existe
        }

        // Agregar cuadros de FECHA y N° DE REPORTE
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(150, 10);  // Cuadro de FECHA
        $pdf->Cell(50, 7, 'FECHA', 1, 2, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 7, date('d/m/Y'), 1, 2, 'C');  // Fecha actual

        // Datos de la empresa a la derecha del logo (ajustado a X=50 para más espacio)
        $pdf->SetXY(45, 10);  // Aumentado de 45 a 50 para evitar superposiciones
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 6, 'MANFRA C.A', 0, 1);  // Nombre de la empresa
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(45, 16);
        $pdf->Cell(0, 5, 'J-123456789', 0, 1);
        $pdf->SetXY(45, 21);
        $pdf->Cell(0, 5, utf8_decode('Dirección: Magnifica C.A'), 0, 1);
        $pdf->SetXY(45, 26);
        $pdf->Cell(0, 5, utf8_decode('Teléfono: 04120444652'), 0, 1);
        $pdf->SetXY(45, 31);
        $pdf->Cell(0, 5, 'Email: manfraca@gmail.com', 0, 1);
        $pdf->Ln(8);  // Espacio adicional para evitar superposiciones verticales

        // Título del reporte
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'LISTA DE ARTICULOS', 0, 1, 'C');
        $pdf->Ln(10);

        // Configurar celdas para los títulos de las columnas
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(58, 6, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(50, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
        $pdf->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 1);
        $pdf->Cell(12, 6, 'Stock', 1, 0, 'C', 1);
        $pdf->Cell(35, 6, utf8_decode('Descripción'), 1, 1, 'C', 1);

        // Obtener datos de la base de datos
        require_once '../modelos/Articulo.php';
        $articulo = new Articulo();
        $rspta = $articulo->listar();

        // Configurar anchos de las celdas
        $pdf->SetWidths(array(58, 50, 30, 12, 35));

        // Llenar la tabla con los datos
        while ($reg = $rspta->fetch_object()) {
            $nombre = utf8_decode($reg->nombre);
            $categoria = utf8_decode($reg->categoria);
            $codigo = $reg->codigo;
            $stock = $reg->stock;
            $descripcion = utf8_decode($reg->descripcion);

            $pdf->SetFont('Arial', '', 10);
            $pdf->Row(array($nombre, $categoria, $codigo, $stock, $descripcion));
        }

        // Mostrar el documento PDF
        $pdf->Output();
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}

ob_end_flush();
?>