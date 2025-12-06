<?php
// Incluir el archivo común donde se configura la zona horaria
include __DIR__ . '/../vistas/connect.php';

function validarDatos($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen) 
{
    // Validar nombre 
    if (empty($nombre)) { return "El nombre no puede estar vacío."; } 
    if (!preg_match("/^[a-zA-Z ]*$/", $nombre)) { return "El nombre solo puede contener letras y espacios."; } 

    // Validar tipo de documento 
    $tiposDocumentoValidos = array("DNI", "RIF", "Pasaporte"); 
    if (!in_array($tipo_documento, $tiposDocumentoValidos)) { return "El tipo de documento no es válido."; } 

    // Validar número de documento 
    if (empty($num_documento)) { return "El número de documento no puede estar vacío."; } 
    if (!is_numeric($num_documento)) { return "El número de documento debe ser numérico."; } 

    // Validar dirección
    if (empty($direccion)) { return "La dirección no puede estar vacía."; } 

    // Validar teléfono 
    if (empty($telefono)) { return "El teléfono no puede estar vacío."; } 
    if (!preg_match("/^[0-9]{10}$/", $telefono)) { return "El teléfono debe contener 10 dígitos."; } 

    // Validar email 
    if (empty($email)) { return "El email no puede estar vacío."; } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { return "El formato del email no es válido."; } 

    // Validar cargo 
    if (empty($cargo)) { return "El cargo no puede estar vacío."; } 

    // Validar login 
    if (empty($login)) { return "El login no puede estar vacío."; } 
    if (strlen($login) < 5) { return "El login debe tener al menos 5 caracteres."; } 

    // Validar clave 
    if (empty($clave)) { return "La clave no puede estar vacía."; } 
    if (strlen($clave) < 8) { return "La clave debe tener al menos 8 caracteres."; } 
    if (!preg_match("/[A-Z]/", $clave)) { return "La clave debe contener al menos una letra mayúscula."; } 
    if (!preg_match("/[a-z]/", $clave)) { return "La clave debe contener al menos una letra minúscula."; } 
    if (!preg_match("/[0-9]/", $clave)) { return "La clave debe contener al menos un número."; } 

    // Validar imagen 
    if (!empty($imagen)) { 
        $extensionesValidas = array("jpg", "jpeg", "png"); 
        $ext = pathinfo($imagen, PATHINFO_EXTENSION); 
        if (!in_array($ext, $extensionesValidas)) { return "La imagen debe ser en formato jpg, jpeg o png."; } 
    } 

    return true; 
}

session_start();

require_once '../modelos/Usuario.php';

// Función para registrar en la bitácora
if (!function_exists('registrarEnBitacora')) {
    function registrarEnBitacora($mensaje) {
        $archivoBitacora = '../logs/bitacora.log';
        if (!is_dir('../logs')) {
            mkdir('../logs', 0755, true);
        }
        // **ELIMINAMOS LA LÍNEA QUE OBTIENE LA IP**
        // $ip = obtenerIPReal();
        //  o  (si no implementaste obtenerIPReal())
        // $ip = $_SERVER['REMOTE_ADDR'];

        // Modificamos la línea de registro para NO incluir la IP:
        $registro = '[' . date('Y-m-d h:i:s A') . '] [' . ($_SESSION['nombre'] ?? 'Sistema') . '] ' . $mensaje . PHP_EOL;
        file_put_contents($archivoBitacora, $registro, FILE_APPEND);
    }
}

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$permiso = isset($_POST["permiso"]) ? $_POST["permiso"] : [];

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if (in_array($_FILES['imagen']['type'], ["image/jpg", "image/jpeg", "image/png"])) {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/usuarios/" . $imagen);
            }
        }

        if (empty($idusuario)) {
            // Insertar nuevo usuario
            $clavehash = password_hash($clave, PASSWORD_DEFAULT);
            // Asegurar que el permiso de Escritorio esté siempre incluido
            if (!in_array(1, $permiso)) {
                $permiso[] = 1;
            }
            $rspta = $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permiso);
            echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
        } else {
            // Editar usuario existente
            if (empty($clave)) {
                // Si la contraseña está vacía, no la actualizamos
                // Asegurar que el permiso de Escritorio esté siempre incluido
                if (!in_array(1, $permiso)) {
                    $permiso[] = 1;
                }
                $rspta = $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $_POST["claveactual"], $imagen, $permiso);
            } else {
                // Si se ingresa una nueva contraseña, la actualizamos
                $clavehash = password_hash($clave, PASSWORD_DEFAULT);
                // Asegurar que el permiso de Escritorio esté siempre incluido
                if (!in_array(1, $permiso)) {
                    $permiso[] = 1;
                }
                $rspta = $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permiso);
            }
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
        break;

    case 'desactivar':
        $rspta = $usuario->desactivar($idusuario);
        echo $rspta ? "Usuario desactivado" : "Usuario no se pudo desactivar";
        break;

    case 'activar':
        $rspta = $usuario->activar($idusuario);
        echo $rspta ? "Usuario activado" : "Usuario no se pudo activar";
        break;

    case 'mostrar':
        $rspta = $usuario->mostrar($idusuario);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $usuario->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->tipo_documento,
                "2" => $reg->num_documento,
                "3" => $reg->telefono,
                "4" => $reg->email,
                "5" => $reg->login,
                "6" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px'>",
                "7" => ($reg->condicion) ?
                    '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>',
                "8" => ($reg->condicion) ?
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idusuario . ')"><li class="fa fa-close"></li></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><li class="fa fa-pencil"></li></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->idusuario . ')"><li class="fa fa-check"></li></button>'
            );
        }
        $results = array(
            "sEcho" => 1, // Informacion para el datable
            "iTotalRecords" => count($data), // Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total de registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'permisos':
        // Obtenemos los permisos de la tabla permisos
        require_once '../modelos/Permiso.php';
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        // Obtener los permisos del usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);

        // Declaramos el array para almacenar todos los permisos marcados
        $valores = array();

        // Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->idpermiso);
        }

        while ($reg = $rspta->fetch_object()) {
            $sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';

            echo '<li>
                    <input type="checkbox" name="permiso[]" value="' . $reg->idpermiso . '" ' . $sw . '>
                    ' . $reg->nombre . '
                </li>';
        }
        break;

    case 'verificar':
        $logina = $_POST['logina'] ?? null; // Para usuarios
        $correo = $_POST['correo'] ?? null; // Para suscripciones
        $clavea = $_POST['clavea'] ?? null; // Contraseña

        // Hash de la clave para compararla con la almacenada
        $clavehash = hash("SHA256", $clavea);

        // Verificación de usuario
        if ($logina && $clavea) {
            $rsptaUsuario = $usuario->verificarCredenciales('usuario', $logina, $clavehash);
            $fetchUsuario = $rsptaUsuario->fetch_object();

            if (isset($fetchUsuario)) {
                // Las credenciales del usuario son correctas
                // Aquí puedes iniciar sesión, establecer sesiones, etc.
                echo "Inicio de sesión exitoso para el usuario.";
            } else {
                echo "Credenciales de usuario incorrectas.";
            }
        }

        // Verificación de suscripción
        if ($correo && $clavea) {
            $rsptaSuscripcion = $usuario->verificarCredenciales('suscripciones', $correo, $clavehash);
            $fetchSuscripcion = $rsptaSuscripcion->fetch_object();

            if (isset($fetchSuscripcion)) {
                // Las credenciales de la suscripción son correctas
                // Aquí puedes proceder con la lógica de suscripción
                echo "Suscripción verificada exitosamente.";
            } else {
                echo "Credenciales de suscripción incorrectas.";
            }
        }

        if (isset($fetch)) {
            // Declarando variables de sesión
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            // Obtenemos los permisos del usuario
            $permisos = $usuario->listarmarcados($fetch->idusuario);

            // Array para almacenar los permisos
            $valores = array();

            while ($per = $permisos->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }

            // Determinando los accesos del usuario
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
            in_array(3, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;
            in_array(4, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
            in_array(5, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
            in_array(6, $valores) ? $_SESSION['consultac'] = 1 : $_SESSION['consultac'] = 0;
            in_array(7, $valores) ? $_SESSION['consultav'] = 1 : $_SESSION['consultav'] = 0;
        }

        // Retornando JSON
        echo json_encode($fetch); 
        break;

        case 'salir':
            registrarEnBitacora("Cerró sesión"); // Registra solo el mensaje de cierre de sesión
        
            session_unset(); // Limpiamos las variables de sesión
            session_destroy(); // Destruimos la sesión
            header("Location: ../vistas/inicio.php");
            break;
}
?>