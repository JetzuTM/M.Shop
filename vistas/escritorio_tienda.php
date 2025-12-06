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
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Tienda | MultiShop</title>
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
    </head>
    <body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
    <div class="logo"></div>
        <a class="navbar-brand" style="font-family: 'Poppins', sans-serif; font-size: 1.75rem; font-weight: bold; color: white; text-transform: uppercase; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
            Multishop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
    <a class="nav-link" href='login.php'>Inicio</a>
</li>
                <li class="nav-item">
                    <a class="nav-link active" href="escritorio_tienda.php">Productos</a>
                    <li class="nav-item">
    <a class="nav-link" href="#" id="compras-link">Compras</a></li>
                <!-- Ícono del carrito -->
                <li class="nav-item">
    <a class="nav-link" href="#" id="carrito-link">
        <i class="fas fa-shopping-cart cart-icon"></i>
        <span id="cart-count" class="badge bg-danger">0</span>
    </a></li></ul>
                    <li class="fa-solid fa-bell cart-icon" style="cart-icon"></li> 
            <span id="cart-count2" class="badge bg-danger">0</span></a></li> 
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
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <h5 class="mb-4 text-center card-title">Lista de Productos</h5>

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
<footer class="py-5 bg-dark">
                <div class="container">
                    <p class="m-0 text-center text-white">Copyright &copy; Todos los derechos reservados by MANFRA C.A</p>
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