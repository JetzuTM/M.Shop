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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
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
        <style>
          /* Theme Variables & Base Styles */
          :root {
            --primary: #6e15c2;
            --primary-dark: #4c0d86;
            --primary-light: #9b59d6;
            --accent: #f59e0b;
            --bg: #f5f3ff;
            --bg2: #ffffff;
            --bg3: #f0ebfa;
            --text: #1e1333;
            --text-muted: #6b5c8a;
            --border: rgba(110,21,194,0.12);
            --card-shadow: 0 4px 24px rgba(110,21,194,0.08);
            --card-shadow-hover: 0 12px 40px rgba(110,21,194,0.18);
            --radius: 16px;
            --transition: 0.35s cubic-bezier(0.4,0,0.2,1);
          }
          body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background-color: var(--bg);
            color: var(--text);
          }
          
          /* Navbar Override */
          #navbar {
            background: var(--primary);
            box-shadow: 0 4px 24px rgba(76,13,134,0.35);
            padding: 10px 0;
            margin-bottom: 30px;
          }
          .nav-brand {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff !important;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
          }
          #navbar .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            transition: var(--transition);
          }
          #navbar .nav-link:hover, #navbar .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
          }
          .nav-icon-btn {
            color: rgba(255,255,255,0.85);
            font-size: 1.2rem;
            transition: var(--transition);
            text-decoration: none;
            padding: 8px 12px;
            position: relative;
            display: inline-flex;
            align-items: center;
          }
          .nav-icon-btn:hover { color: #fff; }
          .nav-badge {
            position: absolute; top: 0px; right: 2px;
            background: var(--accent);
            color: #000; font-size: 0.65rem; font-weight: 700;
            border-radius: 50%; padding: 2px 5px;
          }
          
          /* Cards & Blocks */
          .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
            margin-bottom: 20px;
          }
          .card:hover { box-shadow: var(--card-shadow-hover); }
          .card-header {
            background: var(--bg3) !important;
            border-bottom: 1px solid var(--border) !important;
            color: var(--primary);
            font-weight: 700;
            padding: 1rem 1.25rem;
          }
          .list-group-item.active {
            background-color: rgba(110,21,194,0.1) !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
            font-weight: 600;
          }
          .btn-custom, .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 10px !important;
          }
          .btn-custom:hover, .btn-primary:hover {
            box-shadow: 0 4px 15px rgba(110,21,194,0.4) !important;
            transform: translateY(-2px);
          }
          .btn-secondary {
            background: #fff !important;
            color: var(--primary) !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 10px !important;
            font-weight: 600;
          }
          .btn-secondary:hover {
            background: rgba(110,21,194,0.05) !important;
            border-color: rgba(110,21,194,0.3) !important;
          }
          .dropdown-menu {
             border-radius: 12px;
             border: 1px solid var(--border);
             box-shadow: var(--card-shadow-hover);
          }
          .notification-dropdown {
            border-radius: 12px !important;
            border: 1px solid var(--border) !important;
            box-shadow: var(--card-shadow-hover) !important;
          }
        </style>
</head>
<body>
<!-- ========== NAVBAR ========== -->
<nav id="navbar" class="navbar navbar-expand-lg sticky-top">
  <div class="container-fluid px-4">
      <a href="login.php" class="nav-brand">
        <img src="../public/img/Multi.png" alt="MultiShop Logo" style="height: 40px; margin-right: 8px;">
        MultiShop
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background: rgba(255,255,255,0.15);">
          <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
              <li class="nav-item"><a class="nav-link" href="login.php">Inicio</a></li>
              <li class="nav-item"><a class="nav-link" href="escritorio_tienda.php">Productos</a></li>
              <li class="nav-item"><a class="nav-link active" href="compras.php" id="compras-link">Compras</a></li>
              <li class="nav-item position-relative mx-1">
                  <a class="nav-icon-btn" href="carrito.php" id="carrito-link">
                      <i class="fas fa-shopping-cart"></i>
                      <span id="cart-count" class="nav-badge">0</span>
                  </a>
              </li>
              <li class="nav-item position-relative mx-1">
                  <a class="nav-icon-btn" href="#" id="notificationBell">
                      <i class="fa-solid fa-bell"></i>
                      <span id="notificationCount" class="nav-badge">0</span>
                  </a>
                  <div id="notificationDropdown" class="dropdown-menu dropdown-menu-end notification-dropdown position-absolute" style="display: none; top: 100%; right: 0; min-width: 300px; z-index: 1050;">
                      <div class="notification-header border-bottom p-3 bg-light rounded-top">
                          <h6 class="mb-0 text-dark fw-bold">Notificaciones</h6>
                      </div>
                      <div class="notification-body p-3 text-muted">
                          <p class="mb-0">No hay notificaciones aún.</p>
                      </div>
                      <button id="markAsRead" class="btn btn-light w-100 rounded-bottom border-top text-primary">Marcar como leídas</button>
                  </div>
              </li>
              <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                  <div class="d-flex">
                      <div class="btn-group">
                          <a href="inicio.php" class="btn btn-custom px-4 <?php echo isset($_SESSION['idusuario']) ? 'dropdown-toggle' : ''; ?>" <?php echo isset($_SESSION['idusuario']) ? 'data-bs-toggle="dropdown"' : ''; ?>>
                              <i class="fas fa-user me-2"></i> <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Iniciar Sesión'; ?>
                          </a>
                          <?php if (isset($_SESSION['idusuario'])): ?>
                              <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item" href="./perfil-usuario.php">Perfil</a></li>
                                  <li><a class="dropdown-item text-danger" href="../ajax/usuario.php?op=salir">Cerrar Sesión</a></li>
                              </ul>
                          <?php endif; ?>
                      </div>
                  </div>
              </li>
          </ul>
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
