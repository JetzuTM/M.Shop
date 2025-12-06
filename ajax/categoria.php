<?php
    
    require_once '../modelos/Categoria.php';

    $categoria = new Categoria();

    $idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
    $nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
    $descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idcategoria)){
                $rspta=$categoria->insertar($nombre,$descripcion);
                echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
            }
            else {
                $rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
                echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $categoria->desactivar($idcategoria);
                echo $rspta ? "Categoria desactivada" : "Categoria no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $categoria->activar($idcategoria);
            echo $rspta ? "Categoria activada" : "Categoria no se pudo activar";
        break;

        case 'mostrar':
            $rspta = $categoria->mostrar($idcategoria);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $categoria->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>$reg->nombre,
                    "1"=>$reg->descripcion,
                    "2"=>($reg->condicion) ?
                         '<span class="label bg-green">Activado</span>'
                         :      
                         '<span class="label bg-red">Desactivado</span>',
                     "3"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>'
                        
                );
            }

            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
            break; // <---  AÑADIR ESTE BREAK

        case 'listarCategorias': // <--- MOVER ESTE CASE FUERA DEL WHILE
            $rspta = $categoria->listar();
            $data = array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "id" => $reg->idcategoria,
                    "nombre" => $reg->nombre
                );
            }
            echo json_encode($data);
            break;
    }

?>