<?php
session_start();
if (!isset($_SESSION['consultav'])) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
  exit;
}

define('BACKUP_PATH', './backup/'); // Asegúrate de que esta ruta sea correcta

// Obtener la lista de archivos .sql en la carpeta de respaldos
$files = glob(BACKUP_PATH . '*.sql');

if ($files === false) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Error al leer la carpeta de respaldos.']);
  exit;
}

// Extraer solo los nombres de los archivos
$fileNames = array_map('basename', $files);

header('Content-Type: application/json');
echo json_encode(['success' => true, 'files' => $fileNames]);
?>