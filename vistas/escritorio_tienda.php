<?php
session_start();

$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$correo_usuario = isset($_SESSION['correo']) ? $_SESSION['correo'] : null;

// Ahora puedes usar $id_del_usuario, $nombre_usuario y $correo_usuario seguramente
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <title>Escritorio de Tienda</title>
        <script src="../assets/jquery/jquery.min.js"></script>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap CSS -->
        <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" >
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>   

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <!-- Estilos personalizados -->
        <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css"> 
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
          .form-control {
            background: var(--bg2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: var(--text);
          }
          .form-control:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(110,21,194,0.1);
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
              <li class="nav-item"><a class="nav-link active" href="escritorio_tienda.php">Productos</a></li>
              <li class="nav-item"><a class="nav-link" href="#" id="compras-link">Compras</a></li>
              <li class="nav-item position-relative mx-1">
                  <a class="nav-icon-btn" href="#" id="carrito-link">
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
        <div class="container mt-5">
            <h4 class="mb-4 text-center" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--primary);">Lista de Productos</h4>

            <div class="main-content">  <!-- Contenedor principal -->
                <div class="category-column">  <!-- Columna de categorías -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Categorías</h5>
                        </div>
                        <div class="card-body category-buttons">
                            <ul class="list-group" id="categoryList"></ul>
                        </div>
                    </div>
                </div>

                <div class="product-column">  <!-- Columna de productos -->
                    <!-- Filtros -->
                    <div class="filters row mb-3">
                        <div class="nav-item">
                            <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="&#128269; Buscar producto..." aria-label="Search" id="searchInput">
                            </form>
                        </div>
                    </div>

                    <!-- Productos en tarjetas -->
                    <div class="row" id="productGrid">
                        <?php  // Tu código PHP para generar las tarjetas de productos ?>
                    </div>
                </div>
            </div>
        </div>
                        <!-- Las categorías se cargarán aquí -->
                    </ul>
                </div>
            </div>
        </div>
    <?php
    include("../config/conexion.php");

    // Consulta para contar la cantidad de artículos
    $countQuery = "SELECT COUNT(*) as total_articulos FROM articulo";
    $countResult = $conexion->query($countQuery);
    $totalArticulos = $countResult->fetch_assoc()['total_articulos'];
    ?>
    <!-- Filtros -->
    <div class="filters row mb-3">
</div>
<footer id="footer" style="background: #fff; border-top: 1px solid var(--border); padding: 36px 0 24px; margin-top: 40px;">
    <div class="container text-center">
        <h4 style="color: var(--primary); font-family: 'Outfit', sans-serif; font-weight: 800;">MultiShop</h4>
        <p class="text-muted" style="font-size: 0.85rem; margin-top: 8px;">Copyright &copy; Todos los derechos reservados by MANFRA C.A</p>
    </div>
</footer>
            <script src="../public/bootstrap/js/bootstrap.min.js"></script>
        <script>
        function estaAutenticado() {
    return Boolean(<?php echo isset($_SESSION['idusuario']) ? 'true' : 'false'; ?>);
}
            </script>
<!-- Script para manejo del carrito -->
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
        const totalItems = cart.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cart-count').textContent = totalItems;
    }

    function updateCartCountFromStorage() {
        const cartFromStorage = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cartFromStorage.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cart-count').textContent = totalItems;
    }

    document.addEventListener('DOMContentLoaded', updateCartCountFromStorage);

   //Función para mostrar alerta personalizada, dependiendo de la condición de inicio de sesión
function mostrarAlerta(titulo, texto, tipo){
    Swal.fire({
    icon: tipo,
    title: titulo,
    text: texto,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Iniciar sesión',
    cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al usuario a la página de inicio de sesión
            window.location.href = 'inicio.php';
        }
    });
} 
    // Función para adjuntar los event listeners a los botones "Añadir al carrito"
    function attachAddToCartListeners() {
        document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            if (!estaAutenticado()) {
                mostrarAlerta('Debes iniciar sesión', 'Para añadir productos al carrito, por favor inicia sesión.', 'warning');
                return;
            }
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-nombre');
                const productStock = parseInt(this.getAttribute('data-stock'));
                const productPrice = parseFloat(this.getAttribute('data-precio'));
                const productImage = this.getAttribute('data-imagen');
                const quantityInput = this.closest('.card-body').querySelector('.quantity-input');
                let cantidad = parseInt(quantityInput.value);

                if (cantidad > 0 && cantidad <= productStock) {
                    const existingProductIndex = cart.findIndex(item => item.idarticulo === productId);
                    if (existingProductIndex > -1) {
                        const existingProduct = cart[existingProductIndex];
                        if (existingProduct.cantidad + cantidad <= productStock) {
                            existingProduct.cantidad += cantidad;
                            updateCart();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `No puedes agregar más de ${productStock} unidades de ${productName} al carrito.`
                            });
                            return;
                        }
                    } else {
                        cart.push({
                            idarticulo: productId,
                            nombre: productName,
                            stock: productStock,
                            precio: productPrice,
                            imagen: productImage,
                            cantidad: cantidad
                        });
                        updateCart();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '¡Agregado al carrito!',
                        showConfirmButton: false,
                         // timer: 1500 // Puedes remover esto si no quieres que la alerta desaparezca automáticamente
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `No puedes agregar más de ${productStock} unidades de ${productName} al carrito.`
                    });
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarTodosLosProductos();
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const products = document.querySelectorAll('#productGrid .col-md-3');

        products.forEach(product => {
            const productName = product.querySelector('.card-title').textContent.toLowerCase();
            if (productName.includes(searchText)) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    });

    function cargarCategorias() {
        $.ajax({
            url: '../ajax/categoria.php?op=listarCategorias',
            type: 'GET',
            success: function(response) {
                const categories = JSON.parse(response);
                const categoryList = document.getElementById('categoryList');
                categoryList.innerHTML = '';

                const allButton = document.createElement('button');
                allButton.className = 'list-group-item btn';
                allButton.textContent = 'Todos';
                allButton.onclick = () => {
                    cargarTodosLosProductos();
                    setActiveCategory(allButton);
                };
                categoryList.appendChild(allButton);

                categories.forEach(category => {
                    const button = document.createElement('button');
                    button.className = 'list-group-item btn';
                    button.textContent = category.nombre;
                    button.onclick = () => {
                        filtrarProductos(category.id);
                        setActiveCategory(button);
                    };
                    categoryList.appendChild(button);
                });
            },
            error: function() {
                document.getElementById('categoryList').innerHTML = 'No se pudieron cargar las categorías.';
            }
        });
    }

    function setActiveCategory(activeButton) {
        document.querySelectorAll('#categoryList .list-group-item').forEach(button => {
            button.classList.remove('active');
        });
        activeButton.classList.add('active');
    }

    function cargarTodosLosProductos() {
        $.ajax({
            url: 'cargar_todos_los_productos.php',
            type: 'GET',
            success: function(response) {
                const productGrid = document.getElementById('productGrid');
                productGrid.innerHTML = response; 
                attachAddToCartListeners(); // Adjuntar listeners después de cargar
                setActiveCategory(document.querySelector('#categoryList .list-group-item:first-child'));
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudieron cargar los productos.'
                });
            }
        });
    }
    function filtrarProductos(categoryId) {
        $.ajax({
            url: 'filtrar_productos.php',
            type: 'POST',
            data: { categoryId: categoryId },
            success: function(response) {
                const productGrid = document.getElementById('productGrid');
                productGrid.innerHTML = response; 
                attachAddToCartListeners(); // Adjuntar listeners después de filtrar
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudieron cargar los productos.'
                });
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        cargarCategorias();
        cargarTodosLosProductos();
    });
    document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (localStorage.getItem('productAdded') === 'true') {
            Swal.fire({
                icon: 'success',
                title: '¡Agregado al carrito!',
                showConfirmButton: false,
                timer: 1500
            });
            localStorage.removeItem('productAdded');
        }
    }, 500);
});
// Alerta en Compras
document.addEventListener('DOMContentLoaded', function() {
    const comprasLink = document.getElementById('compras-link');

    comprasLink.addEventListener('click', function(event) {
        event.preventDefault();
        if (!estaAutenticado()) {
            mostrarAlerta('Debes iniciar sesión', 'Para ver tus compras, por favor inicia sesión.', 'warning');
        } else {
            window.location.href = 'compras.php';
        }
    });
});

// Alerta de Carrito
document.addEventListener('DOMContentLoaded', function() {
    const carritoLink = document.getElementById('carrito-link');

    carritoLink.addEventListener('click', function(event) {
        event.preventDefault();
        if (!estaAutenticado()) {
            mostrarAlerta('Debes iniciar sesión', 'Para ver tu carrito, por favor inicia sesión.', 'warning');
        } else {
            window.location.href = 'carrito.php';
        }
    });
});
</script>
    </body>
</html>