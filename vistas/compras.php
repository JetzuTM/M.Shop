<?php
session_start();
require_once "../config/conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["idusuario"]) && !isset($_SESSION["correo"])) {
    header("Location: escritorio_tienda.php");
    exit();
}

$idUsuario = $_SESSION["idusuario"] ?? $_SESSION["correo"];

// Consulta para obtener los pedidos del usuario actual
$sql = "SELECT p.idpedido, p.fecha, p.estado, p.entrega, p.referencia_pago,
               dp.idarticulo, dp.cantidad, dp.precio,
               a.nombre as nombre_articulo, a.imagen,
               d.iddelivery as id_delivery, d.nombre as nombre_delivery
        FROM pedidos p
        JOIN detalle_pedido dp ON p.idpedido = dp.idpedido
        JOIN articulo a ON dp.idarticulo = a.idarticulo
        LEFT JOIN delivery d ON p.id_delivery = d.iddelivery
        WHERE p.idusuario = ?
        ORDER BY p.fecha DESC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $idUsuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$pedidos = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $idpedido = $row['idpedido'];
    if (!isset($pedidos[$idpedido])) {
        $pedidos[$idpedido] = [
            'fecha' => $row['fecha'],
            'estado' => $row['estado'],
            'entrega' => $row['entrega'],
            'referencia_pago' => $row['referencia_pago'], // Añadir la referencia de pago
            'nombre_delivery' => $row['nombre_delivery'] ?? 'No asignado',
            'articulos' => []
        ];
    }
    $pedidos[$idpedido]['articulos'][] = [
        'nombre' => $row['nombre_articulo'],
        'cantidad' => $row['cantidad'],
        'precio' => $row['precio'],
        'imagen' => $row['imagen']
    ];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras - MultiShop</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/tienda_estilo.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
    <div class="logo"></div>
        <a class="navbar-brand" href="#" style="font-family: 'Poppins', sans-serif; font-size: 1.75rem; font-weight: bold; color: white; text-transform: uppercase; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
            MultiShop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                    <a class="nav-link" href="login.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="escritorio_tienda.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="compras.php">Compras</a>
                </li>
                <!-- Ícono del carrito -->
                <li class="nav-item">
                    <a class="nav-link" href="carrito.php">
                        <i class="fas fa-shopping-cart cart-icon"></i>
                        <span id="cart-count" class="badge bg-danger"></span> <!-- Contador de productos en el carrito -->
                    </a>
                    <li class="nav-item position-relative">
                    <a class="nav-link" href="#" id="notificationBell">
    <i class="fa-solid fa-bell cart-icon"></i>
    <span id="notificationCount" class="badge bg-danger"></span>
</a>
<div id="notificationDropdown" class="dropdown-menu dropdown-menu-end notification-dropdown">
    <div class="notification-header">
        <h6>Notificaciones</h6>
    </div>
    <div class="notification-body">
        <p>No hay notificaciones aún.</p>
    </div>
<!-- Botón para marcar notificaciones como leídas -->
<button id="markAsRead" class="btn btn-secondary">Marcar como leídas</button>
</div>
</li>
                <form class="d-flex" onsubmit="return !estaAutenticado() ? Swal.fire({icon: 'warning', title: 'Debes iniciar sesión', text: 'Para ver tu carrito, por favor inicia sesión.'}).then(() => {window.location.href = 'inicio.php'; return false;}) : true;">
    <div class="btn-group">
        <a href="inicio.php" class="btn btn-custom <?php echo isset($_SESSION['idusuario']) ? 'dropdown-toggle' : ''; ?>" <?php echo isset($_SESSION['idusuario']) ? 'data-bs-toggle="dropdown"' : ''; ?>>
            <i class="fas fa-user"></i> <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Iniciar Sesión'; ?>
        </a>
        <?php if (isset($_SESSION['idusuario'])): ?>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="./perfil-usuario.php">Perfil</a></li>
                <li><a class="dropdown-item" href="../ajax/usuario.php?op=salir">Cerrar Sesión</a></li>
            </ul>
        <?php endif; ?>
    </div>
</form>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="cart-title mb-0">Mis Compras</h3>
                    <a href="escritorio_tienda.php" class="btn btn-secondary">
                        <i class="fas fa-long-arrow-alt-left"></i> Volver a la tienda
                    </a>
                </div>
                
                <?php if (empty($pedidos)): ?>
                    <p>No tienes pedidos realizados.</p>
                <?php else: ?>
                    <?php foreach ($pedidos as $idpedido => $pedido): ?>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="cart-title2">Pedido ID <?php echo $idpedido; ?></h5>
                <?php
                    $fechaHora = new DateTime($pedido['fecha']);
                    $fecha = $fechaHora->format('d/m/Y');
                    $hora = $fechaHora->format('H:i:s');
                ?>
                <p class="mb-0">Fecha: <?php echo $fecha; ?></p>
                <p class="mb-0">Hora: <?php echo $hora; ?></p>
                <p class="mb-0">Delivery: <?php echo $pedido['entrega'] ? 'Sí' : 'No'; ?></p>
                <p class="mb-0">Repartidor: <?php echo $pedido['nombre_delivery']; ?></p>
                <p class="mb-0">Referencia del Pago: <?php echo $pedido['referencia_pago'] ?: 'No disponible'; ?></p>
            </div>
            <div class="text-end">
                <?php if ($pedido['estado'] == 'Aceptado'): ?>
                    <button class="btn btn-success me-2"><?php echo $pedido['estado']; ?></button>
                    
                <?php elseif ($pedido['estado'] == 'Rechazado'): ?>
                    <button class="btn btn-danger me-2"><?php echo $pedido['estado']; ?></button>
                <?php elseif ($pedido['estado'] == 'Finalizado'): ?>
                    <button class="btn btn-orange me-2"><?php echo $pedido['estado']; ?></button>
                    <button class="btn btn-primary me-2 calificar-btn" data-pedido-id="<?php echo $idpedido; ?>">
                        Calificar pedido
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary me-2"><?php echo $pedido['estado']; ?></button>
                <?php endif; ?>
                <!-- Botón de descargar PDF -->
<button class="btn btn-azul descargar-pdf-btn" data-pedido-id="<?php echo $idpedido; ?>">
    <i class="fa-solid fa-file"></i>
</button>
                <!-- Botón de ver/ocultar pedido con icono de ojo naranja -->
                <button class="btn btn-orange view-order-btn" data-pedido-id="<?php echo $idpedido; ?>">
                    <i class="fas fa-eye" id="eye-icon-<?php echo $idpedido; ?>"></i>
                </button>
                <div class="mt-2">
                    <strong>Total: Bs <?php echo number_format(array_sum(array_map(function($a) { return $a['precio'] * $a['cantidad']; }, $pedido['articulos'])), 2); ?></strong>
                </div>
            </div>
        </div>
        <div class="card-body card product-card pedido-detalles" id="pedido-<?php echo $idpedido; ?>" style="display: none;">
            <?php foreach ($pedido['articulos'] as $index => $articulo): ?>
                <div class="row mb-2">
                    <div class="col-md-2">
                        <img src="../files/articulos/<?php echo $articulo['imagen']; ?>" 
                             class="img-fluid rounded-3" 
                             alt="<?php echo $articulo['nombre']; ?>">
                    </div>
                    <div class="col-md-8">
                        <h5><div class="cart-title3"><?php echo $articulo['nombre']; ?></h5>
                        <h5>Bs <?php echo number_format($articulo['precio'] * $articulo['cantidad'], 2); ?>
                        <p>Cantidad: <?php echo $articulo['cantidad']; ?></p></h5>
                    </div>
                </div>
                <?php if ($index < count($pedido['articulos']) - 1): ?>
                    <div class="product-separator"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="scripts/compras.js"></script>
<script>
    function updateCartCountFromStorage() {
        const cartFromStorage = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cartFromStorage.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cart-count').textContent = totalItems;
    }

    document.addEventListener('DOMContentLoaded', updateCartCountFromStorage);
</script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.8/sweetalert2.min.js"></script>
</body>
</html>
