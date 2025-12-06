<?php
require '../config/conexion.php';

class Delivery
{
    public function __construct()
    {

    }

    public function insertar( $nombre,  $descripcion, $imagen, $telefono, $direccion)
    {
        $sql = "INSERT INTO 
                    delivery (

                        nombre,
                        descripcion,
                        imagen,
                        telefono,
                        direccion,
                        condicion
                    ) 
                VALUES (
                    '$nombre',
                    '$descripcion',
                    '$imagen',
                    '$telefono',
                    '$direccion',
                    '1')";
        
        return ejecutarConsulta($sql);
    }

    public function editar($iddelivery, $nombre, $direccion, $descripcion, $telefono, $imagen)
{
    $sql = "UPDATE delivery SET 
            nombre = '$nombre', 
            direccion = '$direccion',
            descripcion = '$descripcion',
            telefono = '$telefono'";

    if (!empty($imagen)) {
        $sql .= ", imagen = '$imagen'";
    }

    $sql .= " WHERE iddelivery='$iddelivery'";

    
    
    return ejecutarConsulta($sql);
}


    public function desactivar($iddelivery)
    {
        $sql= "UPDATE delivery SET condicion='0' 
               WHERE iddelivery='$iddelivery'";
        
        return ejecutarConsulta($sql);
    }

    public function activar($iddelivery)
    {
        $sql= "UPDATE delivery SET condicion='1' 
               WHERE iddelivery='$iddelivery'";
        
        return ejecutarConsulta($sql);
    }

    public function mostrar($iddelivery)
    {
        $sql = "SELECT * FROM delivery 
                WHERE iddelivery='$iddelivery'";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT 
                iddelivery,
                codigo,
                nombre,
                stock,
                precio_venta,
                descripcion,
                imagen,
                condicion FROM delivery";

        return ejecutarConsulta($sql);
    }

    public function listarActivos()
    {
        $sql = "SELECT 
                iddelivery, 
                codigo,
                nombre,
                stock,
                precio_venta,
                descripcion,
                imagen,
                condicion
                FROM delivery
                WHERE condicion = '1'";

        return ejecutarConsulta($sql);
    }

    public function listarActivosVenta()
    {
        $sql = "SELECT 
                iddelivery, 
                codigo,
                nombre,
                stock,
                precio_venta,
                descripcion,
                imagen,
                condicion
                FROM delivery 
                WHERE condicion = '1'";

        return ejecutarConsulta($sql);
    }
}
?>