<?php
session_start();
if (!isset($_SESSION['consultav'])) {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

// Incluir el archivo de conexión y la función de bitácora
include './connect.php'; // Asegúrate de que la ruta sea correcta

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $filename = $_POST['filename'];
  $backupDir = 'backup/'; // Asegúrate de que esta ruta sea correcta
  $filePath = $backupDir . $filename;

  if (file_exists($filePath)) {
    if (unlink($filePath)) {
      // Registrar éxito en la bitácora
      registrarEnBitacora("Respaldo borrado correctamente: $filename");
      echo json_encode(['success' => true, 'message' => 'Respaldo borrado correctamente.']);
    } else {
      // Registrar error en la bitácora
      registrarEnBitacora("Error al borrar el respaldo: $filename");
      echo json_encode(['success' => false, 'message' => 'Error al borrar el respaldo.']);
    }
  } else {
    // Registrar error en la bitácora
    registrarEnBitacora("El archivo no existe: $filename");
    echo json_encode(['success' => false, 'message' => 'El archivo no existe.']);
  }
} else {
  // Registrar error en la bitácora
  registrarEnBitacora("Método no permitido en delete_backup.php");
  echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>