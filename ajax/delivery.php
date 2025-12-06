<?php
require_once '../modelos/Delivery.php';

$delivery = new Delivery();

$iddelivery = isset($_POST["iddelivery"]) ? limpiarCadena($_POST["iddelivery"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$imagen = isset($_POST["imagenactual"]) ? limpiarCadena($_POST["imagenactual"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        // Obtener los valores del formulario
        $iddelivery = isset($_POST["iddelivery"]) ? $_POST["iddelivery"] : "";
        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
        $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : "";
        $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : "";
        $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : "";
        $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : ""; // Si no hay imagen nueva, se mantiene la actual

        // Si se sube una nueva imagen, se maneja de esta forma
        if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != "") {
            // Manejo de la nueva imagen
            $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
            if (in_array($_FILES['imagen']['type'], ["image/jpg", "image/jpeg", "image/png"])) {
                $imagen = round(microtime(true)) . '.' . $ext;
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/empleados/" . $imagen);
            }
        } else {
            $imagen = $_POST["imagenactual"];  // Mantén la imagen actual si no se ha cargado una nueva
        }

        // Llamar a la función para guardar o actualizar el registro
        if (empty($iddelivery)) {
            // Insertar el nuevo registro
            $rspta = $delivery->insertar($nombre, $direccion, $descripcion, $telefono, $imagen);
            echo $rspta ? "Registro guardado correctamente" : "No se pudo guardar el registro";
        } else {
            // Actualizar el registro existente
            $rspta = $delivery->editar($iddelivery, $nombre, $direccion, $descripcion, $telefono, $imagen);
            echo $rspta ? "Registro actualizado correctamente" : "No se pudo actualizar el registro";
        }
        break;

    case 'desactivar':
        $rspta = $delivery->desactivar($iddelivery);
        echo $rspta ? "Artículo desactivado" : "No se pudo desactivar el empleado";
        break;

    case 'activar':
        $rspta = $delivery->activar($iddelivery);
        echo $rspta ? "Artículo activado" : "No se pudo activar el empleado";
        break;

    case 'mostrar':
        $rspta = $delivery->mostrar($iddelivery);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $delivery->listar();
        $data = [];

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => "<img src='../files/empleados/" . $reg->imagen . "' height='50px' width='50px'>",
                "2" => ($reg->condicion) ?
                    '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>',
                "3" => ($reg->condicion) ?
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->iddelivery . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->iddelivery . ')"><li class="fa fa-close"></li></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->iddelivery . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->iddelivery . ')"><li class="fa fa-check"></li></button>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
?>
