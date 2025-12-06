<?php
session_start();

// Datos de sesión del usuario
$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$correo_usuario = isset($_SESSION['correo']) ? $_SESSION['correo'] : null;

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

// Consultar datos del usuario
$query_user = "SELECT telefono, fecha_suscripcion, cedula, imagen_perfil FROM suscripciones WHERE id = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $id_del_usuario);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

// Consultar el número de pedidos realizados por el usuario
$query_order_count = "SELECT COUNT(*) as total_pedidos FROM pedidos WHERE idusuario = ?";
$stmt_order_count = $conn->prepare($query_order_count);
$stmt_order_count->bind_param("i", $id_del_usuario);
$stmt_order_count->execute();
$result_order_count = $stmt_order_count->get_result();
$order_count = $result_order_count->fetch_assoc()['total_pedidos'];

// Consultar últimas compras del usuario, incluyendo el nombre del artículo
$query_orders = "
    SELECT p.fecha, d.idarticulo, d.cantidad, d.precio, a.nombre AS nombre_articulo
    FROM pedidos p
    JOIN detalle_pedido d ON p.idpedido = d.idpedido
    JOIN articulo a ON d.idarticulo = a.idarticulo
    WHERE p.idusuario = ?
    ORDER BY p.fecha DESC LIMIT 5";
$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->bind_param("i", $id_del_usuario);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
$orders = $result_orders->fetch_all(MYSQLI_ASSOC);

// Cerrar la conexión
$stmt_user->close();
$stmt_order_count->close();
$stmt_orders->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3e5f5;
        }
        .bg-purple {
            background-color: #6e15c2;
        }
        .text-purple {
            color: #6e15c2;
        }
        .btn-purple {
            background-color: #6e15c2;
            color: white;
        }
        .btn-purple:hover {
            background-color: black;
            color: white;
        }
        .perfil-image {
    width: 150px; /* Tamaño estándar */
    height: 150px; /* Tamaño estándar */
    border-radius: 50%; /* Hace la imagen circular */
    object-fit: cover; /* Ajusta la imagen sin deformarla */
    object-position: center; /* Centra la imagen */
    display: block;
    margin: 0 auto; /* Centra la imagen en su contenedor */
}
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                <div class="card-body text-center bg-purple text-white py-5 position-relative">
    <!-- Contenedor de la imagen de perfil -->
    <div class="position-relative d-inline-block">
        <?php if (!empty($user_data['imagen_perfil'])): ?>
            <img src="<?php echo htmlspecialchars($user_data['imagen_perfil']); ?>" alt="avatar" class="perfil-image">
        <?php endif; ?>
        <!-- Botón de edición (media luna) -->
        <button type="button" class="btn btn-light btn-sm position-absolute bottom-0 end-0 rounded-pill" style="transform: translate(25%, 25%);" data-bs-toggle="modal" data-bs-target="#modalCambiarImagen">
            <i class="fas fa-camera"></i> <!-- Ícono de cámara -->
        </button>
    </div>
    <h5 class="my-3"><?php echo htmlspecialchars($nombre_usuario); ?></h5>
    <p class="text-white-50 mb-1"><?php echo htmlspecialchars($correo_usuario); ?></p>
    <p class="text-white-50 mb-4">Cédula: <?php echo htmlspecialchars($user_data['cedula']); ?></p>
</div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="mb-1"><span class="text-purple me-2"><i class="fas fa-user"></i></span> Miembro desde: <?php echo htmlspecialchars($user_data['fecha_suscripcion']); ?></p>
                        <p class="mb-0"><span class="text-purple me-2"><i class="fas fa-shopping-cart"></i></span> Pedidos realizados: <?php echo $order_count; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="text-purple mb-4">Información del Perfil</h2>
                        <form method="POST" action="editar_perfil.php">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <label for="fullName" class="form-label">Nombre completo</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="fullName" name="nombre" value="<?php echo htmlspecialchars($nombre_usuario); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <label for="email" class="form-label">Email</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="correo" value="<?php echo htmlspecialchars($correo_usuario); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" id="phone" name="telefono" value="<?php echo htmlspecialchars($user_data['telefono']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-purple">Guardar cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="text-purple mb-4">Últimas Compras</h2>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                                <div class="row mb-3">
                                    <div class="col-sm-8">
                                        <p class="mb-0"><span class="text-purple me-2"><i class="fas fa-box"></i></span> Artículo: <?php echo htmlspecialchars($order['nombre_articulo']); ?> (Cantidad: <?php echo htmlspecialchars($order['cantidad']); ?>)</p>
                                    </div>
                                    <div class="col-sm-4 text-end">
                                        <p class="text-muted mb-0">Fecha: <?php echo htmlspecialchars($order['fecha']); ?></p>
                                        <p class="text-muted mb-0">Precio: $<?php echo htmlspecialchars(number_format($order['precio'], 2)); ?></p>
                                    </div>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No tienes compras recientes.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <a href="escritorio_tienda.php" class="btn btn-purple">Volver</a>
        </div>
    </div>


<!-- Modal para cambiar la imagen -->
<div class="modal fade" id="modalCambiarImagen" tabindex="-1" aria-labelledby="modalCambiarImagenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarImagenLabel">Cambiar imagen de perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="./php/img_perfil.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nueva_imagen" class="form-label">Selecciona una nueva imagen</label>
                        <input type="file" class="form-control" id="nueva_imagen" name="nueva_imagen" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-purple">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
