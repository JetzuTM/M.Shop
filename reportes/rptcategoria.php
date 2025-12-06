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

        // Agregar una página al documento PDF
        $pdf->AddPage();

        // Configurar márgenes
        $y_axis_initial = 25;

        // Agregar el logo y nombre de MultiShop en el encabezado
        $pdf->Image('../public/img/Multi.png', 10, 10, 30); // Ajusta la ruta y posición del logo
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'MultiShop', 0, 1, 'C'); // Nombre de la empresa
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'RIF: J-123456789-0', 0, 1, 'C'); // RIF ficticio
        $pdf->Ln(10); // Espacio después del encabezado

        // Título del reporte
        $pdf->SetFont('Arial', 'B', 14); // Aumentar el tamaño de la fuente del título
        $pdf->Cell(0, 6, 'LISTA DE CATEGORIAS', 0, 1, 'C');
        $pdf->Ln(10);

        // Configurar celdas para los títulos de las columnas
        $pdf->SetFillColor(232, 232, 232); // Fondo gris claro
        $pdf->SetFont('Arial', 'B', 12); // Aumentar el tamaño de la fuente de los títulos de las columnas
        $pdf->Cell(80, 8, 'Nombre', 1, 0, 'C', 1); // Aumentar el ancho de la celda
        $pdf->Cell(70, 8, utf8_decode('Descripción'), 1, 0, 'C', 1); // Aumentar el ancho de la celda
        $pdf->Cell(40, 8, utf8_decode('Estado'), 1, 1, 'C', 1); // Aumentar el ancho de la celda

        // Obtener datos de la base de datos
        require_once '../modelos/Categoria.php';
        $categoria = new Categoria();
        $rspta = $categoria->listar();

        // Configurar anchos de las celdas
        $pdf->SetWidths(array(80, 70, 40)); // Aumentar el ancho de las celdas

        // Llenar la tabla con los datos
        while ($reg = $rspta->fetch_object()) {
            $nombre = utf8_decode($reg->nombre);
            $descripcion = utf8_decode($reg->descripcion);
            $estado = ($reg->condicion == 1) ? 'Activo' : 'Inactivo'; // Convertir estado a texto

            $pdf->SetFont('Arial', '', 12); // Aumentar el tamaño de la fuente en las celdas
            $pdf->Row(array($nombre, $descripcion, $estado));
        }

        // Mostrar el documento PDF
        $pdf->Output();
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}

// Liberar el buffer
ob_end_flush();
?>