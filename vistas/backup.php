<?php
error_reporting(0); // Deshabilita los errores de PHP
ini_set('display_errors', 0); // Deshabilita la visualización de errores

include './connect.php'; // Asegúrate de que la ruta sea correcta

// Define los directorios y base de datos
define('BD', 'dbsistema'); 
define('BACKUP_PATH', './backups/'); 
define('LOG_PATH', './logs/bitacora.log'); // Define la ruta del archivo de log

// Asegúrate de tener acceso al nombre de usuario que realiza el respaldo.
// Aquí asumimos que el nombre de usuario está en una variable de sesión $_SESSION['username']
session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Desconocido';

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

$day = date("d");
$mont = date("m");
$year = date("Y");
$hora = date("H-i-s");
$fecha = $day . '/' . $mont . '/' . $year;
$DataBASE = "MultiShop_" . str_replace('/', '-', $fecha) . "-(" . $hora . "_hrs).sql";
$tables = array();
$error = 0; 

$result = SGBD::sql('SHOW TABLES');
if ($result) {
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
    $sql = '-- MultiShop Backup' . "\n\n";
    $sql .= 'SET FOREIGN_KEY_CHECKS=0;' . "\n\n";
    $sql .= 'CREATE DATABASE IF NOT EXISTS ' . BD . ";\n\n";
    $sql .= 'USE ' . BD . ";\n\n";

    foreach ($tables as $table) {
        $result = SGBD::sql('SELECT * FROM ' . $table);
        if ($result) {
            $numFields = mysqli_num_fields($result);
            $sql .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysqli_fetch_row(SGBD::sql('SHOW CREATE TABLE ' . $table));
            $sql .= "\n\n" . $row2[1] . ";\n\n";

            while ($row = mysqli_fetch_row($result)) {
                $sql .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $numFields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $sql .= '"' . $row[$j] . '"';
                    } else {
                        $sql .= '""';
                    }
                    if ($j < ($numFields - 1)) {
                        $sql .= ',';
                    }
                }
                $sql .= ");\n";
            }
            $sql .= "\n\n\n";
        } else {
            $error = 1; 
        }
    }

    if ($error == 1) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al generar el respaldo de la base de datos.']);
        registrarEnBitacora("Error al generar el respaldo de la base de datos");
        exit;
    } else {
        if (!is_dir(BACKUP_PATH)) {
            mkdir(BACKUP_PATH, 0755, true); 
        }
        chmod(BACKUP_PATH, 0777); 
        $sql .= 'SET FOREIGN_KEY_CHECKS=1;';
        $handle = fopen(BACKUP_PATH . $DataBASE, 'w+');
        if (fwrite($handle, $sql)) {
            fclose($handle);
            registrarEnBitacora("Respaldo de base de datos generado exitosamente");
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Respaldo generado exitosamente.', 'filename' => $DataBASE]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al escribir el archivo de respaldo.']);
            registrarEnBitacora("Error al escribir el archivo de respaldo");
            exit;
        }
    }
}
?>