<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  
  if(strlen(session_id()) < 1) //Si la variable de session no esta iniciada
  {
    session_start();
  } 

  if(!isset($_SESSION["nombre"]))
  {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
  }

  else  //Agrega toda la vista
  {

    if($_SESSION['compras'] == 1)
    {
        require 'PDF_MC_Table.php';

        //instanciamos a la clase
        $pdf = new PDF_MC_Table();

        //Agregamos la primera pagina al documento PDF
        $pdf->AddPage();

        //Margenes de 25 pixeles
        $y_axis_initial = 25;

        //Tipo de letra y titulo de la pagina (no es encabezado)
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(100,6,'RELACION DE COMPRAS',1,0,'C');
        $pdf->Ln(10);

        //Celdas para los titulos de cada columna y asignamos color y tipo letra
        $pdf->SetFillColor(232,232,232); //fondo gris RGB
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(25,6,'Fecha',1,0,'C',1);
        $pdf->Cell(25,6,'Proveedor',1,0,'C',1);
        $pdf->Cell(20,6,'Usuario',1,0,'C',1);
        $pdf->Cell(25,6,'Comprobante',1,0,'C',1);
        $pdf->Cell(30,6,'N/comprobante',1,0,'C',1);
        $pdf->Cell(30,6,utf8_decode('Total de compra'),1,0,'C',1);
        $pdf->Cell(20,6,'Impuesto',1,0,'C',1);
        $pdf->Cell(25,6,utf8_decode('Estado'),1,0,'C',1);
        $pdf->Ln(10);

        //Filas de los registros segun la consulta Mysql
        require_once '../modelos/Ingreso.php';
        $ingreso = new Ingreso();
        
        $rspta = $ingreso->listar();

        //Implementamos las celdas de la tabla con los registros a mostrar
        $pdf->SetWidths(array(25,25,20,25,30,30,20,25,)); //Anchos de las celdas (igula al de las de arrriba)

        while ($reg = $rspta->fetch_object()) 
        {
          
            
            $fecha= $reg->fecha;
            $proveedor= $reg->proveedor;
            $usuario= $reg->usuario;
            $tipo_comprobante= $reg->tipo_comprobante;
            $num_comprobante= $reg->num_comprobante;
            $total_compra= $reg->total_compra;
            $impuesto= $reg->impuesto;
            $estado = $reg->estado;
           

            $pdf->SetFont('Arial','',10);
            $pdf->Row(array(
                utf8_decode($fecha),
                utf8_decode($proveedor),
                utf8_decode($usuario),
                utf8_decode($tipo_comprobante),
                utf8_decode($num_comprobante),
                utf8_decode($total_compra),
                utf8_decode($impuesto),
                utf8_decode($estado),
            ));
        }

        //Mostramos el documento PDF
        $pdf->Output();

    } 

    else
    {
        echo 'No tiene permiso para visualizar el reporte';
    }


   }
   ob_end_flush(); //liberar el espacio del buffer
?>