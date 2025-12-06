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
        $pdf->Cell(100,6,'RELACION DE PROVEEDORES',1,0,'C');
        $pdf->Ln(10);

        //Celdas para los titulos de cada columna y asignamos color y tipo letra
        $pdf->SetFillColor(232,232,232); //fondo gris RGB
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,6,'Nombre',1,0,'C',1);
        $pdf->Cell(30,6,'N/ Documento',1,0,'C',1);
        $pdf->Cell(35,6,'Direccion',1,0,'C',1);
        $pdf->Cell(30,6,'Telefono',1,0,'C',1);
        $pdf->Cell(35,6,'Email',1,0,'C',1);
        $pdf->Ln(10);

        //Filas de los registros segun la consulta Mysql
        require_once '../modelos/Persona.php';
        $persona = new Persona();
        
        $rspta = $persona->listarp();

        //Implementamos las celdas de la tabla con los registros a mostrar
        $pdf->SetWidths(array(30,30,35,30,35,)); //Anchos de las celdas (igula al de las de arrriba)

        while ($reg = $rspta->fetch_object()) 
        {
          
            
            $nombre= $reg->nombre;
            $num_documento= $reg->num_documento;
            $direccion= $reg->direccion;
            $telefono= $reg->telefono;
            $email = $reg->email;
           

            $pdf->SetFont('Arial','',10);
            $pdf->Row(array(
                utf8_decode($nombre),
                utf8_decode($num_documento),
                utf8_decode($direccion),
                utf8_decode($telefono),
                utf8_decode($email),
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