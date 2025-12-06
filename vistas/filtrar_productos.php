<?php
require_once '../config/conexion.php'; // Asegúrate de que este archivo tiene la conexión a la base de datos.

if (!isset($_POST['categoryId']) || !is_numeric($_POST['categoryId'])) {
    // Validar si el ID de la categoría es válido.
    echo '<div class="col-12"><p class="text-center text-muted">Categoría no válida o no seleccionada.</p></div>';
    exit;
}

$categoryId = intval($_POST['categoryId']); // Convertir el ID de la categoría a un entero para evitar inyección SQL.

// Consulta para obtener los productos de la categoría seleccionada.
$query = "
    SELECT 
        a.idarticulo,
        a.nombre,
        a.stock,
        a.imagen,
        a.precio_compra,
        a.precio_venta,
        c.nombre AS categoria_nombre
    FROM articulo a
    INNER JOIN categoria c ON a.idcategoria = c.idcategoria
    WHERE a.stock > 0 AND a.idcategoria = ? AND a.condicion = 1
";
// Añadir filtro de categoría si se proporciona
if ($categoryId !== null) {
    $query .= " AND c.idcategoria = $categoryId";
}

// Preparar la consulta para evitar inyección SQL.
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $categoryId); // Usar el ID de la categoría como parámetro.
$stmt->execute();
$result = $stmt->get_result(); // Ejecutar la consulta y obtener los resultados.

if ($result->num_rows > 0) {
    // Generar el HTML para cada producto.
    while ($row = $result->fetch_assoc()) {
        $stock = isset($row['stock']) ? $row['stock'] : "No disponible";
        $precioVenta = isset($row['precio_venta']) ? $row['precio_venta'] : "No disponible";

        echo '
        <div class="col-md-3 col-sm-6 mb-4"> <!-- Cambiado a col-md-3 para hacer las tarjetas más pequeñas -->
            <div class="card">
                <img src="../files/articulos/' . htmlspecialchars($row['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nombre']) . '" style="height: 150px; object-fit: cover;"> <!-- Ajustar el tamaño de la imagen -->
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 1rem;">' . htmlspecialchars($row['nombre']) . '</h5> 
                    <p class="card-text"><strong></strong> Bs ' . htmlspecialchars($precioVenta) . '</p>
                    <p class="card-text"><strong>Disponible:</strong> ' . htmlspecialchars($stock) . '</p>
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm px-4 py-2 add-to-cart"
    data-id="' . htmlspecialchars($row['idarticulo']) . '" 
    data-nombre="' . htmlspecialchars($row['nombre']) . '" 
    data-stock="' . htmlspecialchars($stock) . '" 
    data-precio="' . htmlspecialchars($precioVenta) . '" 
    data-imagen="' . htmlspecialchars($row['imagen']) . '">
    <i class="fas fa-cart-plus"></i> Añadir
</a>
                    <input type="number" class="btn btn-custom2  w-25 quantity-input" min="1" max="' . htmlspecialchars($stock) . '" value="1" />
                </div>
            </div>
        </div>';
    }
} else {
    // Si no hay productos disponibles.
    echo '<div class="col-12"><p class="text-center text-muted">No hay productos disponibles.</p></div>';
}

// Cerrar la conexión.
$stmt->close();
$conexion->close();
?>
