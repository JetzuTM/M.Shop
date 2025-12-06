<?php
require '../config/conexion.php';

class Articulo 
{
    public function __construct()
    {

    }

    public function insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $precio_compra, $precio_venta)
    {
        $sql = "INSERT INTO 
                    articulo (
                        idcategoria,
                        codigo,
                        nombre,
                        stock,
                        descripcion,
                        imagen,
                        precio_compra,
                        precio_venta,
                        condicion
                    ) 
                VALUES (
                    '$idcategoria',
                    '$codigo',
                    '$nombre',
                    '$stock',
                    '$descripcion',
                    '$imagen',
                    '$precio_compra',
                    '$precio_venta',
                    '1')";
        
        return ejecutarConsulta($sql);
    }

    public function editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $precio_compra, $precio_venta)
    {
        $sql = "UPDATE articulo SET 
                idcategoria ='$idcategoria',
                codigo = '$codigo', 
                nombre = '$nombre', 
                stock = '$stock', 
                descripcion = '$descripcion', 
                imagen = '$imagen',
                precio_compra = '$precio_compra',
                precio_venta = '$precio_venta'
                WHERE idarticulo='$idarticulo'";
        
        return ejecutarConsulta($sql);
    }

    public function desactivar($idarticulo)
    {
        $sql= "UPDATE articulo SET condicion='0' 
               WHERE idarticulo='$idarticulo'";
        
        return ejecutarConsulta($sql);
    }

    public function activar($idarticulo)
    {
        $sql= "UPDATE articulo SET condicion='1' 
               WHERE idarticulo='$idarticulo'";
        
        return ejecutarConsulta($sql);
    }

    public function mostrar($idarticulo)
    {
        $sql = "SELECT * FROM articulo 
                WHERE idarticulo='$idarticulo'";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT 
                a.idarticulo, 
                a.idcategoria, 
                c.nombre as categoria,
                a.codigo,
                a.nombre,
                a.stock,
                a.descripcion,
                a.imagen,
                a.precio_compra,
                a.precio_venta,
                a.condicion 
                FROM articulo a 
                INNER JOIN categoria c 
                ON a.idcategoria = c.idcategoria";

        return ejecutarConsulta($sql);
    }

    public function listarActivos()
    {
        $sql = "SELECT 
                a.idarticulo, 
                a.idcategoria, 
                c.nombre as categoria,
                a.codigo,
                a.nombre,
                a.stock,
                a.descripcion,
                a.imagen,
                a.precio_compra,
                a.precio_venta,
                a.condicion 
                FROM articulo a 
                INNER JOIN categoria c 
                ON a.idcategoria = c.idcategoria
                WHERE a.condicion = '1'";

        return ejecutarConsulta($sql);
    }

    public function listarActivosVenta()
    {
        $sql = "SELECT 
                a.idarticulo, 
                a.idcategoria, 
                c.nombre as categoria,
                a.codigo,
                a.nombre,
                a.stock,
                a.precio_venta,
                a.descripcion,
                a.imagen,
                a.condicion
                FROM articulo a 
                INNER JOIN categoria c 
                ON a.idcategoria = c.idcategoria
                WHERE a.condicion = '1'";

        return ejecutarConsulta($sql);
    }
}
?>