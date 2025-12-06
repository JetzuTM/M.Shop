<?php
// Iniciar sesión para obtener el usuario actual
if (strlen(session_id()) < 1) {
    session_start();
}

include './connect.php'; // Asegúrate de que la ruta sea correcta

// Función para registrar en la bitácora
function registrarEnBitacora($mensaje) {
    $archivoBitacora = '../logs/bitacora.log';
    if (!is_dir('../logs')) {
        mkdir('../logs', 0777, true);
    }
    $usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'sistema';
    // Cambiar el formato de la hora a 12 horas con AM/PM
    $registro = '[' . date('Y-m-d h:i:s A') . '] [' . $usuario . '] ' . $mensaje . PHP_EOL;
    file_put_contents($archivoBitacora, $registro, FILE_APPEND);
}

// Configuración de la base de datos
$host = 'localhost'; // Host de la base de datos
$user = 'root'; // Usuario de MySQL
$password = ''; // Contraseña de MySQL
$database = 'dbsistema'; // Nombre de la base de datos

// Verificar si se ha enviado un archivo
if (!isset($_FILES['sql_file'])) {
    registrarEnBitacora('Error: No se ha subido ningún archivo para restauración');
    die(json_encode(['status' => 'error', 'message' => 'No se ha subido ningún archivo.']));
}

if ($_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
    registrarEnBitacora('Error en la subida del archivo para restauración: código ' . $_FILES['sql_file']['error']);
    die(json_encode(['status' => 'error', 'message' => 'Hubo un error en la subida del archivo.']));
}

// Obtener el archivo subido
$uploaded_file = $_FILES['sql_file']['tmp_name'];
$file_name = $_FILES['sql_file']['name'];


// Verificar que el archivo sea un SQL
if (pathinfo($file_name, PATHINFO_EXTENSION) !== 'sql') {
    registrarEnBitacora('Error: El archivo subido para restauración no es un archivo SQL');
    die(json_encode(['status' => 'error', 'message' => 'El archivo debe ser un archivo SQL (.sql).']));
}

// Conectar solo al servidor MySQL (sin especificar la base de datos)
$mysqli = new mysqli($host, $user, $password);

// Verificar la conexión
if ($mysqli->connect_error) {
    registrarEnBitacora('Error de conexión a MySQL: ' . $mysqli->connect_error);
    die(json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $mysqli->connect_error]));
}

// Función para verificar si la base de datos existe
function baseDeDatosExiste($mysqli, $database) {
    $result = $mysqli->query("SHOW DATABASES LIKE '$database'");
    return $result->num_rows > 0;
}

// Función para eliminar la base de datos si existe
function eliminarBaseDeDatos($mysqli, $database) {
    if (baseDeDatosExiste($mysqli, $database)) {
        if ($mysqli->query("DROP DATABASE $database")) {
            return true;
        } else {
            registrarEnBitacora("Error al eliminar la base de datos: " . $mysqli->error);
            die(json_encode(['status' => 'error', 'message' => 'Error al eliminar la base de datos: ' . $mysqli->error]));
        }
    }
    return false;
}

// Eliminar la base de datos si existe
if (eliminarBaseDeDatos($mysqli, $database)) {
    $response = ['status' => 'success', 'message' => "Base de datos '$database' eliminada correctamente."];
} else {
    registrarEnBitacora("La base de datos '$database' no existía, se creará una nueva");
    $response = ['status' => 'info', 'message' => "La base de datos '$database' no existía."];
}

// Crear la base de datos
$create_db_query = "CREATE DATABASE $database";
if ($mysqli->query($create_db_query)) {
    $response = ['status' => 'success', 'message' => "Base de datos '$database' creada correctamente."];
} else {
    registrarEnBitacora("Error al crear la base de datos: " . $mysqli->error);
    die(json_encode(['status' => 'error', 'message' => 'Error al crear la base de datos: ' . $mysqli->error]));
}

// Seleccionar la base de datos
$mysqli->select_db($database);

// Función para verificar si el archivo SQL corresponde a la base de datos correcta
function verificarArchivoSQL($file_path, $database) {
    $content = file_get_contents($file_path);
    // Verificar si el archivo contiene el nombre de la base de datos
    if (strpos($content, "`$database`") !== false || strpos($content, "$database") !== false) {
        return true;
    }
    return false;
}

// Verificar si el archivo SQL corresponde a la base de datos correcta
if (verificarArchivoSQL($uploaded_file, $database)) {
    // Leer y ejecutar el archivo de respaldo
    $commands = file_get_contents($uploaded_file);
    if ($mysqli->multi_query($commands)) {
        registrarEnBitacora("Base de datos restaurada exitosamente desde: " . $file_name);
        
        // Registrar en la tabla backup_logs si existe
        try {
            // Esperar a que terminen todas las consultas
            while ($mysqli->more_results() && $mysqli->next_result()) {
                // Procesar los resultados pendientes para limpiar
                if ($res = $mysqli->store_result()) {
                    $res->free();
                }
            }
            
            $usuario = isset($_SESSION['nombre']) ? $mysqli->real_escape_string($_SESSION['nombre']) : 'sistema';
            $ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
            $archivo = $mysqli->real_escape_string($file_name);
            
            $query = "INSERT INTO backup_logs (fecha, usuario, ip, nombre_archivo, tipo_operacion) 
                      VALUES (NOW(), '$usuario', '$ip', '$archivo', 'restauración')";
            
            if (!$mysqli->query($query)) {
            }
        } catch (Exception $e) {
            // Solo registrar este error, no interrumpir el flujo
        }
        
        $response = ['status' => 'success', 'message' => "Base de datos restaurada desde: " . $file_name];
    } else {
        registrarEnBitacora("Error al restaurar la base de datos: " . $mysqli->error);
        $response = ['status' => 'error', 'message' => "Error al restaurar la base de datos: " . $mysqli->error];
    }
} else {
    registrarEnBitacora("Error: El archivo SQL no corresponde a la base de datos '$database'");
    $response = ['status' => 'error', 'message' => "El archivo SQL no corresponde a la base de datos '$database'."];
}

// Cerrar la conexión
$mysqli->close();

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>