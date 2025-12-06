<?php
require_once "../config/conexion.php";

$op = $_GET['op'];

if($op == 'registrar_visto') {
    $idusuario = $_POST['idusuario'];
    $video_id = $_POST['video_id'];
    $video_src = $_POST['video_src'];
    
    // Verificar si ya existe un registro para este usuario y video
    $sql = "SELECT * FROM videos_vistos 
            WHERE idusuario = $idusuario AND video_id = '$video_id'";
    $resultado = mysqli_query($conexion, $sql);
    
    if(mysqli_num_rows($resultado) == 0) {
        // Insertar nuevo registro
        $sql = "INSERT INTO videos_vistos (idusuario, video_id, video_src, fecha_visto)
                VALUES ($idusuario, '$video_id', '$video_src', NOW())";
        mysqli_query($conexion, $sql);
    }
    
    echo json_encode(['success' => true]);
}