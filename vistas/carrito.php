<?php
session_start();
require_once "../config/conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["idusuario"]) && !isset($_SESSION["correo"])) {
    header("Location: escritorio_tienda.php");
    exit();
}

$idUsuario = $_SESSION["idusuario"] ?? $_SESSION["correo"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Carrito - Pasarela de Pago</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" >
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
          
          /* Forms Re-styled */
          .form-control, .form-select, .payment-section {
            background: var(--bg2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: var(--text);
          }
          .payment-section {
            padding: 28px;
            box-shadow: var(--card-shadow);
          }
          .form-control:focus, .form-select:focus {
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
              <li class="nav-item"><a class="nav-link" href="escritorio_tienda.php">Productos</a></li>
              <li class="nav-item"><a class="nav-link" href="compras.php" id="compras-link">Compras</a></li>
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

<script>
function redirectToLogin() {
    window.location.href = "login.php";
}
</script>

<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Carrito de Compras -->
        <div class="col-lg-7 col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                        <a href="escritorio_tienda.php" class="btn btn-secondary">
                            <i class="fas fa-long-arrow-alt-left"></i> Volver a la tienda
                        </a>
                        </h5>
                        
                        <!-- Botón para suspender la compra y limpiar el carrito -->
                        <button class="btn btn-danger" onclick="clearCartAndRedirect()">Suspender compra y limpiar todo</button>
                    </div>
                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <p class="cart-title">Carrito de compras</p>
                            <p id="cart-info" class="mb-0">Tienes 0 productos en tu carrito</p>
                        </div>
                    </div>

                    <!-- Lista de productos del carrito -->
                    <div id="cart-items"></div>

                    <!-- Total del carrito -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h5>Total:</h5>
                        <h5 class="cart-total" id="cart-total">Bs 0</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pasarela de Pago -->
        <div class="col-lg-4 col-md-10 mt-4 mt-lg-0">
            <div class="payment-section">
                <h5>Resumen de pago</h5>
                <div class="d-flex justify-content-between">
                    <p>Subtotal</p>
                    <p id="subtotal">Bs 0</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Impuestos</p>
                    <p id="taxes">Bs 0</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Total a pagar</p>
                    <p id="total-payment">Bs 0</p>
                </div>
                <hr>

                <!-- Formulario de Pago -->
                <form onsubmit="handlePayment(event);">
                    <div class="mb-3">
                        <label for="payment-method" class="form-label">Métodos de pago</label>
                        <select class="form-select" id="payment-method" onchange="handlePaymentMethodChange()">
                            <option value="pago">Pago Móvil</option>
                            <option value="tarjeta">Tarjeta de crédito/débito</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div id="payment-form-container">
                        <!-- Pago Móvil -->
                        <div id="mobile-payment-container" style="display: block;">
                            <label class="form-label">Banco de Venezuela</label>
                            <br>
                            <label class="form-label">Nombre: Josber Hernández</label>
                            <br>
                            <label class="form-label">Cédula: V-29554820</label>
                            <br>
                            <label class="form-label">Número de Teléfono: 0412-0444652</label>
                            <br>
                            <label for="mobile-reference" class="form-label">Referencia</label>
                            <input type="text" class="form-control" id="mobile-reference" 
                             maxlength="12" placeholder="Referencia de Pago" 
                             pattern="[0-9]{12}" inputmode="numeric" 
                             onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                        </div>

                        <!-- Tarjeta de crédito/débito -->
                        <div id="card-details" style="display: none;">
                            <label for="card-number" class="form-label">Número de tarjeta</label>
                            <input type="text" class="form-control" id="card-number" placeholder="1234 5678 9012 3456">
                        </div>
                        <div id="expiration-date-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiration-date" class="form-label">Fecha de expiración</label>
                                    <input type="text" class="form-control" id="expiration-date" placeholder="MM/AA">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div id="paypal-container" style="display: none;">
                            <label for="paypal-email" class="form-label">Correo de PayPal</label>
                            <input type="email" class="form-control" id="paypal-email" placeholder="correo@ejemplo.com">
                        </div>
                    </div>

                    <!-- Botón de Pago -->
                    <button type="submit" class="btn btn-custom w-100 py-3 mt-3 shadow-sm fs-5 fw-bold">Pagar Bs <span id="payable-amount">0</span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para Mostrar/Ocultar Formularios de Pago -->
<script>
    function handlePaymentMethodChange() {
        const paymentMethod = document.getElementById('payment-method').value;
        const cardDetails = document.getElementById('card-details');
        const expirationDateContainer = document.getElementById('expiration-date-container');
        const paypalContainer = document.getElementById('paypal-container');
        const mobilePaymentContainer = document.getElementById('mobile-payment-container');

        // Ocultar todos los campos
        cardDetails.style.display = 'none';
        expirationDateContainer.style.display = 'none';
        paypalContainer.style.display = 'none';
        mobilePaymentContainer.style.display = 'none';

        // Mostrar campos según el método de pago seleccionado
        if (paymentMethod === 'tarjeta') {
            cardDetails.style.display = 'block';
            expirationDateContainer.style.display = 'block';
        } else if (paymentMethod === 'paypal') {
            paypalContainer.style.display = 'block';
        } else if (paymentMethod === 'pago') {
            mobilePaymentContainer.style.display = 'block';
        }
    }

    // Inicializar con el método de pago por defecto
    document.addEventListener('DOMContentLoaded', function() {
        handlePaymentMethodChange();
    });
</script>

<!-- Botón para Redireccionar y Limpiar Carrito -->
<script>
    function clearCartAndRedirect() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará todo el contenido del carrito",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.removeItem('cart');
            Swal.fire(
                '¡Eliminado!',
                'El carrito ha sido vaciado.',
                'success'
            ).then(() => {
                window.location.href = 'compras.php';
            });
        }
    });
}

function handlePayment(event) {
    event.preventDefault();
// Obtener los productos del carrito
const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    if (cartItems.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Carrito vacío',
            text: 'No hay productos en el carrito. Por favor, agregue productos antes de realizar la compra.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    Swal.fire({
        title: '¿Confirmar compra?',
        text: "¿Está seguro de que desea realizar la compra?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '¿Desea agregar entrega?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((deliveryResult) => {
                const deliveryOption = deliveryResult.isConfirmed;
                
                const paymentMethod = document.getElementById('payment-method').value;
                let paymentDetails = {};

                // Validar el método de pago seleccionado
                if (paymentMethod === 'pago') {
                    const mobileReference = document.getElementById('mobile-reference').value;
                    if (!mobileReference) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, ingrese la referencia del pago móvil.'
                        });
                        return;
                    }
                    paymentDetails = {
                        method: paymentMethod,
                        reference: mobileReference
                    };
                } else if (paymentMethod === 'tarjeta') {
                    const cardNumber = document.getElementById('card-number').value;
                    const expirationDate = document.getElementById('expiration-date').value;
                    const cvv = document.getElementById('cvv').value;
                    if (!cardNumber || !expirationDate || !cvv) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, complete todos los campos de la tarjeta.'
                        });
                        return;
                    }
                    paymentDetails = {
                        method: paymentMethod,
                        cardNumber: cardNumber,
                        expirationDate: expirationDate,
                        cvv: cvv
                    };
                } else if (paymentMethod === 'paypal') {
                    const paypalEmail = document.getElementById('paypal-email').value;
                    if (!paypalEmail) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, ingrese su correo de PayPal.'
                        });
                        return;
                    }
                    paymentDetails = {
                        method: paymentMethod,
                        email: paypalEmail
                    };
                }

                

                // Crear objeto con toda la información del pedido
                const orderData = {
                    cartItems: cartItems,
                    delivery: deliveryOption,
                    paymentDetails: paymentDetails
                };

                fetch('guardar_pedido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(orderData),
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'La compra se realizó correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            localStorage.removeItem('cart');
                            if (deliveryOption) {
                                window.location.href = 'escritorio_delivery.php';
                            } else {
                                window.location.href = 'compras.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Hubo un error al procesar la compra'
                        });
                        if (data.message === "Usuario no autenticado") {
                            window.location.href = 'login.php';
                        }
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Hubo un error al procesar la compra: ${error.message}`
                    });
                });
            });
        } else {
            window.location.href = 'compras.php';
        }
    });
}
</script>


<!-- Script para Mostrar Productos y Calcular Montos -->
<script>
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function groupCartItems(cart) {
        // Crear un objeto para agrupar los productos
        const groupedItems = {};

        cart.forEach(item => {

            if (groupedItems[item.nombre]) {
                // Si el producto ya existe, sumar su cantidad
                groupedItems[item.nombre].cantidad += item.cantidad;
            } else {
                // Si no existe, añadirlo
                groupedItems[item.nombre] = { ...item };
            }
        });

        // Retornar los productos agrupados como array
        return Object.values(groupedItems);
    }

    function loadCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartInfo = document.getElementById('cart-info');
    const cartTotal = document.getElementById('cart-total');
    const subtotalElem = document.getElementById('subtotal');
    const taxesElem = document.getElementById('taxes');
    const totalPaymentElem = document.getElementById('total-payment');
    const payableAmount = document.getElementById('payable-amount');
    const cartCount = document.getElementById('cart-count');
    
    cartItemsContainer.innerHTML = '';
    let total = 0;
    let totalCantidad = 0; // Variable para contar la cantidad total de productos

    if (cart.length === 0) {
        cartInfo.textContent = 'Tu carrito está vacío.';
        cartCount.textContent = '0';
    } else {
        const groupedCart = groupCartItems(cart);
        cartInfo.textContent = `Tienes ${groupedCart.length} productos únicos en tu carrito.`;
        
        groupedCart.forEach((item) => {
            total += parseFloat(item.precio) * item.cantidad;
            totalCantidad += item.cantidad;

            cartItemsContainer.innerHTML += `
            <div class="card product-card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row align-items-center">
                            <div>
                                <img src="../files/articulos/${item.imagen}" class="img-fluid rounded-3" alt="${item.nombre}">
                            </div>
                            <div class="ms-3">
                                <h5 class="product-name">${item.nombre}</h5>
                                <p class="product-price small mb-0">Precio: Bs ${item.precio}</p>
                                <p class="product-stock">Disponible: ${item.stock}</p>
                                <p class="product-id" style="display: none;">ID: ${item.idarticulo}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center">
                            <div class="me-3">
                                <input type="number" class="form-control form-control-sm quantity-input" value="${item.cantidad}" max="${item.stock}" onchange="updateQuantity('${item.idarticulo}', this.value)">
                            </div>
                            <div class="me-3">
                                <h5 class="mb-0">Bs ${(item.precio * item.cantidad).toFixed(2)}</h5>
                            </div>
                            <a href="javascript:void(0)" class="remove-icon" onclick="removeItem('${item.idarticulo}')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });

        // Actualizar el total del carrito y el monto a pagar
        const taxes = total * 0.16;  // Suponiendo un impuesto del 16%
        const totalPayment = total + taxes;

        cartTotal.textContent = `Bs ${total.toFixed(2)}`;
        subtotalElem.textContent = `Bs ${total.toFixed(2)}`;
        taxesElem.textContent = `Bs ${taxes.toFixed(2)}`;
        totalPaymentElem.textContent = `Bs ${totalPayment.toFixed(2)}`;
        payableAmount.textContent = totalPayment.toFixed(2);

        // Actualizar el contador total de productos
        cartCount.textContent = totalCantidad; // Actualizar el contador con la cantidad total
    }
}

function updateQuantity(idarticulo, cantidad) {
    const item = cart.find(product => product.idarticulo === idarticulo);
    if (item) {
        item.cantidad = parseInt(cantidad);
        if (item.cantidad < 1) item.cantidad = 1;
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItems();
    }
}

    function removeItem(idarticulo) {
        cart = cart.filter(product => product.idarticulo !== idarticulo);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItems();
    }

    // Cargar los productos del carrito al cargar la página
    document.addEventListener('DOMContentLoaded', loadCartItems);
</script>
<!-- Bootstrap JS (opcional, para funcionalidades de Bootstrap como el navbar-toggler) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Actualizar el arreglo de productos y los montos totales en carrito.php
    localStorage.setItem('cart', JSON.stringify(cart));
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {

// Evento para mostrar/hide el dropdown de notificaciones
const notificationBell = document.getElementById('notificationBell');
const notificationDropdown = document.getElementById('notificationDropdown');
const notificationCount = document.getElementById('notificationCount');
const markAsReadButton = document.getElementById('markAsRead');

notificationBell.addEventListener('click', function(event) {
    event.preventDefault();
    notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
    actualizarNotificaciones();
});

// Ocultar el dropdown de notificaciones al hacer clic fuera de él
document.addEventListener('click', function(event) {
    if (!notificationBell.contains(event.target) && !notificationDropdown.contains(event.target)) {
        notificationDropdown.style.display = 'none';
    }
});

markAsReadButton.addEventListener('click', function() {
    marcarNotificacionesComoLeidas().then(() => {
        // Oculta la bandeja de notificación
        notificationDropdown.style.display = 'none';
        // Oculta el icono del contador
        notificationCount.style.display = 'none';
    // }).catch(error => {
    //     mostrarError('Error al marcar notificaciones como leídas:', error.message);
    });
});

function marcarNotificacionesComoLeidas() {
    return fetch('notificaciones.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message);
        }
    })
    // .catch(error => mostrarError('Error al marcar notificaciones como leídas:', error));
}

function actualizarContadorNotificaciones(cantidad) {
    const notificationCount = document.getElementById('notificationCount');
    notificationCount.textContent = cantidad;
}

function actualizarCuerpoNotificaciones(mensaje) {
    const notificationBody = document.querySelector('#notificationDropdown .notification-body p');
    notificationBody.textContent = mensaje;
}

function mostrarError(titulo, mensaje = '') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}
function actualizarNotificaciones() {
    marcarNotificacionesComoLeidas()
        .then(() => {
            fetch('notificaciones.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        actualizarContadorNotificaciones(data.notificaciones);
                        actualizarCuerpoNotificaciones(data.mensaje);

                        // Ocultar el dropdown si no hay nuevas notificaciones
                        if (data.notificaciones === 0) {
                            notificationDropdown.style.display = 'none';
                            notificationCount.style.display = 'none'; // Oculta el icono del contador
                        }
                    } else {
                        mostrarError('Error al obtener notificaciones:', data.message);
                    }
                })
                // .catch(error => {
                //     mostrarError('Error en la solicitud AJAX:', error);
                // });
        })
        // .catch(error => {
        //     mostrarError('Error al marcar notificaciones como leídas:', error);
        // });
}
// Llama a esta función después de marcar las notificaciones como leídas
actualizarNotificaciones();
});    
</script>
</body>
</html>