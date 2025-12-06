<?php
// Incluir el archivo común donde se configura la zona horaria
include __DIR__ . '/../vistas/connect.php';

// Iniciar sesión después de configurar la zona horaria
session_start();

// Incluir otros archivos
include("../config/conexion.php");
include("../modelos/Usuario.php");

// Función para registrar en la bitácora
if (!function_exists('registrarEnBitacora')) {
    function registrarEnBitacora($mensaje) {
        $archivoBitacora = '../logs/bitacora.log';
        if (!is_dir('../logs')) {
            mkdir('../logs', 0777, true);
        }
        $registro = '[' . date('Y-m-d h:i:s A') . '] [' . ($_SESSION['nombre'] ?? 'Sistema') . '] ' . $mensaje . PHP_EOL;
        file_put_contents($archivoBitacora, $registro, FILE_APPEND);
    }
}

// Verificar si se solicita cerrar sesión
if (isset($_GET['cerrar']) && $_GET['cerrar'] === 'true') {
    // Registrar el evento de cierre de sesión (solo para administradores)
    if (isset($_SESSION['nombre']) && isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) {
        registrarEnBitacora("Cierre de sesión como administrador.");
    }

    // Destruir la sesión
    session_unset();
    session_destroy();

    // Redirigir al inicio de sesión
    header("Location: ../vistas/inicio.php");
    exit();
}

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Procesar inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = $_POST['logina'];
    $clave = $_POST['clavea'];

    // Verificar en la tabla de suscripciones (clientes)
    $stmt_suscripciones = $conexion->prepare("SELECT * FROM suscripciones WHERE correo = ?");
    $stmt_suscripciones->bind_param("s", $identificador);
    $stmt_suscripciones->execute();
    $result_suscripciones = $stmt_suscripciones->get_result();

    if ($result_suscripciones->num_rows > 0) {
        $row_suscripciones = $result_suscripciones->fetch_assoc();
        if (password_verify($clave, $row_suscripciones['password_hash'])) {
            $_SESSION['correo'] = $identificador;
            $_SESSION['idusuario'] = $row_suscripciones['id']; // Asumiendo que hay un campo 'id' en la tabla suscripciones
            $_SESSION['nombre'] = $row_suscripciones['nombre']; // Asumiendo que hay un campo 'nombre'
            $_SESSION['mensaje_exito'] = "Inicio de sesión exitoso.";
            header("Location: ../vistas/escritorio_tienda.php");
            exit();
        } else {
            $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos.";
            header("Location: ../vistas/inicio.php");
            exit();
        }
    } else {
        // Verificar en la tabla de usuarios (administradores)
        $stmt_usuario = $conexion->prepare("SELECT * FROM usuario WHERE login = ? AND condicion = 1");
        $stmt_usuario->bind_param("s", $identificador);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario->num_rows > 0) {
            $row_usuario = $result_usuario->fetch_assoc();

            if (password_verify($clave, $row_usuario['clave'])) {
                $_SESSION['login'] = $identificador;
                $_SESSION['nombre'] = $row_usuario['nombre'];
                $_SESSION['idusuario'] = $row_usuario['idusuario'];

                // Obtener permisos
                $usuario = new Usuario();
                $permisos = $usuario->listarmarcados($row_usuario['idusuario']);
                $valores = array();

                while ($per = $permisos->fetch_object()) {
                    array_push($valores, $per->idpermiso);
                }

                // Almacenar permisos en la sesión
                $_SESSION['escritorio'] = in_array(1, $valores) ? 1 : 0;
                $_SESSION['almacen'] = in_array(2, $valores) ? 1 : 0;
                $_SESSION['compras'] = in_array(3, $valores) ? 1 : 0;
                $_SESSION['ventas'] = in_array(4, $valores) ? 1 : 0;
                $_SESSION['acceso'] = in_array(5, $valores) ? 1 : 0;
                $_SESSION['consultac'] = in_array(6, $valores) ? 1 : 0;
                $_SESSION['consultav'] = in_array(7, $valores) ? 1 : 0;

                // Registrar en la bitácora
                registrarEnBitacora("Inició sesión");

                $_SESSION['mensaje_exito'] = "Inicio de sesión exitoso.";
                header("Location: ../vistas/escritorio.php");
                exit();
            } else {
                $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos (admin).";
                header("Location: ../vistas/inicio.php");
                exit();
            }
        } else {
            $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos.";
            header("Location: ../vistas/inicio.php");
            exit();
        }
    }
}

// Cerrar conexión
$conexion->close();
?>