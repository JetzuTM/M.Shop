<?php
session_start();

// Datos de sesión del usuario
$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;

// Si no hay un usuario autenticado, redirigir al login
if (!$id_del_usuario) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "dbsistema";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejo de la subida de la imagen
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "./uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen real
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        echo "El archivo es una imagen - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está establecido en 0 por un error
    if ($uploadOk == 0) {
        echo "Lo sentimos, tu imagen no se pudo subir.";
    } else {
        // Intentar subir el archivo
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "La imagen ". htmlspecialchars(basename($_FILES["image"]["name"])) . " ha sido subida.";

            // Guardar la URL de la imagen en la base de datos
            $query_update_image = "UPDATE suscripciones SET imagen_perfil = ? WHERE id = ?";
            $stmt_update_image = $conn->prepare($query_update_image);
            $stmt_update_image->bind_param("si", $target_file, $id_del_usuario);
            $stmt_update_image->execute();

            if ($stmt_update_image->affected_rows > 0) {
                echo "Imagen de perfil actualizada correctamente.";
            } else {
                echo "Error al actualizar la imagen de perfil.";
            }

            $stmt_update_image->close();
        } else {
            echo "Lo sentimos, hubo un error al subir tu archivo.";
        }
    }
}

$conn->close();
header("Location: perfil-usuario.php");
exit();
?>