<?php
session_start(); // Asegúrate de que esto esté aquí
error_log("Intento de inicio de sesión para: " . $_POST['logina']);
include("../config/conexion.php");
include("../modelos/Usuario.php");

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = $_POST['logina'];
    $clave = $_POST['clavea'];

    // Verificar en la tabla de suscripciones
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
        // Verificar en la tabla de usuarios
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