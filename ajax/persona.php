<?php
    
    require_once '../modelos/Persona.php';

    $persona = new Persona();

    $idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
    $tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
    $nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
    $tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
    $num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
    $direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
    $telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
    $email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : '';


    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idpersona)){
                $rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
                echo $rspta ? "Persona registrada" : "Persona no se pudo registrar";
            }
            else {
                $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
                echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
            } 
        break;

        case 'eliminar':
                $rspta = $persona->eliminar($idpersona);
                echo $rspta ? "Persona eliminada" : "Persona no se pudo eliminar";
        break;

        case 'mostrar':
            $rspta = $persona->mostrar($idpersona);
            echo json_encode($rspta);
        break;

        case 'listarp':
            // Obtener tanto personas como suscripciones
            $rspta = $persona->listarPersonas();
            $data = Array();
            
            if (isset($rspta['personas'])) {
                while ($reg = $rspta['personas']->fetch_object()) {
                    $email = isset($reg->email) ? $reg->email : 'No disponible';  // Verificación de existencia del campo email
                    $data[] = array(
                        "0" => $reg->nombre,
                        "1" => $reg->tipo_documento,
                        "2" => $reg->num_documento,
                        "3" => $reg->telefono,
                        "4" => $email,  // Aseguramos que 'email' no cause problemas
                        "5" =>
                            '<button class="btn btn-warning" onclick="mostrar(' . $reg->idpersona . ')"><li class="fa fa-pencil"></li></button>' .
                            ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idpersona . ')"><li class="fa fa-trash"></li></button>'
                    );
                }
            }
        
            // Procesar las suscripciones si es necesario
            if (isset($rspta['suscripciones'])) {
                foreach ($rspta['suscripciones'] as $suscripcion) {
                    $suscripcion_email = isset($suscripcion['correo']) ? $suscripcion['correo'] : 'No disponible'; // Verificación del correo de suscripción
                    $data[] = array(
                        "0" => isset($suscripcion['nombre']) ? $suscripcion['nombre'] : 'No disponible',
                        "1" => '',
                        "2" => isset($suscripcion['cedula']) ? $suscripcion['cedula'] : 'No disponible',
                        "3" => isset($suscripcion['telefono']) ? $suscripcion['telefono'] : 'No disponible',
                        "4" => $suscripcion_email, // Aseguramos que 'correo' no cause problemas
                        "5" =>
                            '<button class="btn btn-warning" onclick="mostrar(' . $suscripcion['id'] . ')"><li class="fa fa-pencil"></li></button>' .
                            ' <button class="btn btn-danger" onclick="eliminar(' . $suscripcion['id'] . ')"><li class="fa fa-trash"></li></button>'
                    );
                }
            }
        
            // Preparar los resultados para el datatable
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
            break;
        
        case 'listarc':
            // Obtener tanto personas como suscripciones
            $rspta = $persona->listarPersonas();
            $data = Array();
        
            // Procesar las personas
            // Procesar las personas
if (isset($rspta['personas'])) {
    while ($reg = $rspta['personas']->fetch_object()) {
        $data[] = array(
            "0" => $reg->nombre,                  // Persona nombre
            "1" => $reg->tipo_documento,           // Tipo de documento
            "2" => $reg->num_documento,            // Número de documento
            "3" => $reg->telefono,                 // Teléfono
            "4" => $reg->email,                    // Email
            "5" => 
                '<button class="btn btn-warning" onclick="mostrar(' . $reg->idpersona . ')"><li class="fa fa-pencil"></li></button>' .
                ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idpersona . ')"><li class="fa fa-trash"></li></button>'
        );
    }
}

// Procesar las suscripciones si es necesario
if (isset($rspta['suscripciones']) && !empty($rspta['suscripciones'])) {
    foreach ($rspta['suscripciones'] as $suscripcion) {
        $data[] = array(
            "0" => isset($suscripcion['nombre']) ? $suscripcion['nombre'] : 'No disponible',  // Suscripción nombre
            "1" => '',  // Columna vacía
            "2" => isset($suscripcion['cedula']) ? $suscripcion['cedula'] : 'No disponible',  // Suscripción cédula
            "3" => isset($suscripcion['telefono']) ? $suscripcion['telefono'] : 'No disponible', // Suscripción teléfono
            "4" => isset($suscripcion['correo']) ? $suscripcion['correo'] : 'No disponible',   // Suscripción correo
            "5" => 
                '<button class="btn btn-warning" onclick="mostrar(' . $suscripcion['id'] . ')"><li class="fa fa-pencil"></li></button>' .
                ' <button class="btn btn-danger" onclick="eliminar(' . $suscripcion['id'] . ')"><li class="fa fa-trash"></li></button>'
        );
    }
}

        
            // Preparar los resultados para el datatable
            $results = array(
                "sEcho" => 1, // Información para el datatable
                "iTotalRecords" => count($data), // Enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), // Enviamos el total de registros a visualizar
                "aaData" => $data
            );
            echo json_encode($results);
            break;
    }

?>