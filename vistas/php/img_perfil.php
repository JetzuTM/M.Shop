<?php
session_start();

$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;

$host = "localhost";
$user = "root";
$password = "";
$dbname = "dbsistema";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == 0) {
    $carpeta_destino = "../assets/img/uploads/";  // Asegurar la barra al final
    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0775, true);  // Crear carpeta si no existe
    }

    $nombre_archivo = time() . "_" . basename($_FILES['nueva_imagen']['name']); // Evitar nombres duplicados
    $ruta_imagen = $carpeta_destino . $nombre_archivo;

    if (move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $ruta_imagen)) {
        $ruta_guardar = "assets/img/uploads/" . $nombre_archivo; // Guardar solo la ruta relativa

        $query = "UPDATE suscripciones SET imagen_perfil = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $ruta_guardar, $id_del_usuario);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
}

$conn->close();
header("Location: ../perfil-usuario.php");
exit();

?>