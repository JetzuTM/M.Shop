<?php
// Incluye la conexión a la base de datos
include "../config/conexion.php";

// Verifica si la solicitud es GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Consulta para obtener las categorías
  $query = "SELECT * FROM categoria";
  $result = $conexion->query($query);

  // Crea un arreglo para almacenar las categorías
  $categorias = array();

  // Recorre los resultados y agrega las categorías al arreglo
  while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
  }

  // Devuelve las categorías en formato JSON
  echo json_encode($categorias);
}

// Cierra la conexión a la base de datos
$conexion->close();
?>