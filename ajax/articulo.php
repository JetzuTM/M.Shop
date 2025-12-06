<?php
require_once '../modelos/Articulo.php';

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$precio_compra = isset($_POST["precio_compra"]) ? limpiarCadena($_POST["precio_compra"]) : "";
$precio_venta = isset($_POST["precio_venta"]) ? limpiarCadena($_POST["precio_venta"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/articulos/" . $imagen);
            }
        }

        if (empty($idarticulo)) {
            // Insertar el artículo
            $rspta = $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $precio_compra, $precio_venta);
            echo $rspta ? "Artículo registrado correctamente" : "No se pudo registrar el artículo";
        } else {
            // Actualizar el artículo
            $rspta = $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $precio_compra, $precio_venta);
            echo $rspta ? "Artículo actualizado correctamente" : "No se pudo actualizar el artículo";
        }
        break;

    case 'desactivar':
        $rspta = $articulo->desactivar($idarticulo);
        echo $rspta ? "Artículo desactivado" : "No se pudo desactivar el artículo";
        break;

    case 'activar':
        $rspta = $articulo->activar($idarticulo);
        echo $rspta ? "Artículo activado" : "No se pudo activar el artículo";
        break;

    case 'mostrar':
        $rspta = $articulo->mostrar($idarticulo);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $articulo->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->categoria,
                "2" => $reg->codigo,
                "3" => $reg->stock,
                "4" => $reg->precio_compra,
                "5" => $reg->precio_venta,
                "6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>",
                "7" => ($reg->condicion) ?
                    '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>',
                "8" => ($reg->condicion) ?
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <class="" onclick=""><li</li>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->idarticulo . ')"><li class="fa fa-check"></li></button>'
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

    case 'selectCategoria':
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->select();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>