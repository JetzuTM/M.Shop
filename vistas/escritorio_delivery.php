<?php
session_start();

// Verificar si el pedido en curso está en la sesión
if (!isset($_SESSION['id_pedido_en_curso'])) {
    echo json_encode(['success' => false, 'message' => 'No se encontró un pedido en curso. Por favor, realice la compra nuevamente.']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Envios - MultiShop</title>
        <!-- Favicon -->
        <link rel="apple-touch-icon" href="../public/img/Multi.png">
        <link rel="shortcut icon" href="../public/img/Multi.ico">
        <!-- Bootstrap CSS -->
        <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" >
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Estilos personalizados -->
        <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">                
        <style>
        footer {
            text-align: center;
            padding: 1rem 0;
            background-color: #343a40; /* Color de fondo del footer */
            color: white;
        }
            .navbar {
                background-color: #6e15c2;
                padding: 10px 20px;
            }
            .navbar-nav .nav-link {
                color: white;
                font-size: 29px;
                font-weight: bold;
                margin-right: 20px;
                margin-top: 0px;

            }
            .navbar-nav .nav-link:hover {
                color: black;
            }
            .btn-custom {
                background-color: #4c0d86;
                color: white;
                border: 2px solid white;
                font-weight: bold;
            }
            .btn-custom:hover {
                background-color: black;
                color: white;
            }
            .container-fluid {
                max-width: 1300px;
            }
            .card-product {
                margin-bottom: 20px;
            }
            .cart-icon {
                font-size: 1.5rem;
                color: white;
                transition: color 0.3s; /* Agrega una transición suave */
            }

            .cart-icon:hover {
            color: black; /* Mantén el color negro para que no se vea el cambio al pasar el puntero */
        }

            body {
    background-color: #f8f9fa;
}

.container {
    max-width: 1400px; /* Ajusta el ancho máximo del contenedor para un diseño más profesional */
}

.card {
    border: none;
    border-radius: 10px; /* Redondeo de borde para un diseño moderno */
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra más suave para un efecto elegante */
    transition: transform 0.3s, box-shadow 0.3s; /* Transición suave para efectos */
    margin-bottom: 1.5rem; /* Espacio entre tarjetas */
    display: flex;
  justify-content: center;
  align-items: center;
  width: 80%;
  
}

.card:hover {
    transform: translateY(-5px); /* Efecto de hover para un efecto de elevación */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada al hacer hover */
}

.card-img-top {
    height: 200px; /* Ajuste de altura para imágenes */
  object-fit: cover;
  border-radius: 50%;
  padding: 12px;
  width: 200px;
}

.card-body {
    padding: 1.5rem; /* Relleno ajustado para un diseño compacto */
}

.card-title {

    font-size: 1.5rem; /* Tamaño de fuente para títulos más destacado */
    font-weight: bold;
}

.card-text {
    font-size: 1rem; /* Tamaño de fuente más legible */
    color: #6c757d;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 8px; /* Redondeo del borde para botones */
    padding: 0.5rem 1rem; /* Ajuste de relleno */
    font-size: 0.875rem; /* Tamaño de fuente más pequeño */
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: green; /* Color de fondo más oscuro al hacer hover */
}

.filters {
    margin-bottom: 1.5rem; /* Espacio inferior para filtros */
}

.filters input {
    border-radius: 25px; /* Borde redondeado para los campos de entrada */
    padding-left: 1rem; /* Espaciado interior a la izquierda */
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1); /* Sombra interior para un efecto de campo de entrada */
}

.btn_admin {
            cursor: pointer;
            z-index: 999;
            position: absolute;
            top: 15px;
            left: 140px;
            background-color: #7A551C;
                color: white;
                border: 2px solid white;
                font-weight: bold;

        }
  .btn-brown {
  background-color: #68b866 !important;
  color: white !important;
  border: 2px solid black !important;
  padding: 10px 20px !important;
  font-size: 16px !important;
  border-radius: 5px !important;
}

.btn-brown:hover {
  background-color: #31932f !important;
}
    </style>
        
    </head>
    <body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">

        <a class="navbar-brand" href="" style="font-family: 'Roboto', sans-serif; font-size: 1.75rem; font-weight: bold; color: white; text-transform: uppercase; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
            Multishop Envios
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                
                <!-- Ícono del delivery-->
                <div class="nav-link active">
                        <i class="fas fa-motorcycle"></i>
                </div>   
            </ul>
            <form class="d-flex" onsubmit="return redirectToLogin()">
                <button class="btn btn-custom" type="submit">
                    <i class="fas fa-user"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Lista de Repartidores</h2>

    <?php
    include("../config/conexion.php");

    // Consulta para contar la cantidad de artículos
    $countQuery = "SELECT COUNT(*) as total_delivery FROM delivery";
    $countResult = $conexion->query($countQuery);
    $totaldelivery = $countResult->fetch_assoc()['total_delivery'];
    ?>

    <!-- Mostrar la cantidad de artículos -->
    <h5 class="text-center mb-5 text-muted ">Repartidores Disponibles: <strong><?php echo $totaldelivery; ?></strong></h5>

   <!-- Productos en tarjetas -->
<div class="row" id="productGrid">
    <?php
    $query = "
    SELECT iddelivery, nombre, imagen, precio_compra, precio_venta, telefono, direccion
    FROM delivery
    ORDER BY iddelivery DESC
";
    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()) {
        $precioCompra = isset($row['precio_compra']) ? $row['precio_compra'] : "No disponible";
        $direccion = isset($row['direccion']) ? $row['direccion'] : "No disponible";

        echo '
        <div class="col-md-3 mb-1">
            <div class="card">
                <img src="../files/empleados/' . $row['imagen'] . '" class="card-img-top" alt="' . $row['nombre'] . '">
                <div class="card-body">
                    <h2 class="card-title">' . $row['nombre'] . '</h2>
                    <p class="card-text"><strong></strong><h6> Ubicación: ' . $direccion . '</h6></p>
                    <p class="btn btn-primary add-to-cart" onclick="handleButtonClick(event)" 
                        data-id="' . $row['iddelivery'] . '" 
                        data-nombre="' . $row['nombre'] . '" 
                        data-precio="' . $direccion . '" 
                        data-imagen="' . $row['imagen'] . '" 
                        data-telefono="' . $row['telefono'] . '">
                        <i class="fas fa-motorcycle"></i> Solicitar Repartidor
                    </p>
                </div>
            </div>
        </div>';
    }

    $conexion->close();
    ?>
</div>
</div>
<footer class="py-3 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Todos los derechos reservados by MANFRA C.A</p>
        </div>
    </footer>
<!-- Script para manejo del carrito -->
<script>
    let cart = [];

    // Función para actualizar el carrito en el almacenamiento local
    function updateCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
        document.getElementById('cart-count').textContent = cart.length;
    }

    // Event listener para los botones "Añadir al carrito"
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-nombre');
            const productPrice = this.getAttribute('data-precio');
            const productImage = this.getAttribute('data-imagen');
        });
    });

   //Rating de Estrellas
   const ratingElem = document.querySelector('.card-text h5');
const rating = parseFloat(ratingElem.dataset.rating);
const starsElem = document.createElement('i');
starsElem.className = 'fas fa-star';
for (let i = 0; i < rating; i++) {
    starsElem.innerHTML += '<i class="fas fa-star"></i>';
}
ratingElem.parentNode.appendChild(starsElem);

    function redirectToLogin() {
        window.location.href = "login.php";
        return false;
    }
</script>

<script>
    // Selecciona todos los botones con la clase "add-to-cart"
const buttons = document.querySelectorAll('.add-to-cart');

// Asigna el evento de clic a cada botón
buttons.forEach(button => {
  button.addEventListener('click', handleButtonClick);
});

// Función que se ejecuta cuando se presiona el botón
function handleButtonClick(event) {
    event.preventDefault();
    const repartidorNombre = event.target.getAttribute('data-nombre');
    const repartidorId = event.target.getAttribute('data-id');
    const repartidorTelefono = event.target.getAttribute('data-telefono'); // Obtenemos el teléfono del repartidor

    // Nueva alerta de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas solicitar al repartidor ${repartidorNombre}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Solicitar ubicación de referencia
            Swal.fire({
                title: 'Ubicación de Referencia',
                input: 'text',
                inputPlaceholder: 'Ingresa la ubicación de referencia',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Enviar'
            }).then((resultUbicacion) => {
                if (resultUbicacion.isConfirmed) {
                    // Enviar la selección y la ubicación al backend
                    fetch('./guardar_repartidor.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id_delivery: repartidorId, ubicacion: resultUbicacion.value }),
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar alerta de éxito con opción de WhatsApp
                            Swal.fire({
                                icon: 'success',
                                title: `Repartidor ${repartidorNombre} asignado exitosamente.`,
                                text: `Enviale un mensaje para acordar la entrega.`,
                                confirmButtonText: '<i class="fab fa-whatsapp"></i> Whatsapp',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-brown',
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Usar el número de teléfono del repartidor seleccionado
                                    let numeroTelefono = repartidorTelefono; // Número de teléfono del repartidor
                                    let mensaje = `Hola Repartidor ${repartidorNombre}, necesito un envío en la ubicación: ${resultUbicacion.value}`;
                                    let url = `https://wa.me/${numeroTelefono}?text=${encodeURIComponent(mensaje)}`;

                                    // Abre WhatsApp con el mensaje
                                    window.open(url, '_blank');

                                    // Redireccionar a compras.php
                                    window.location.href = "compras.php";
                                }
                            });
                        } else {
                            alert("Error al asignar el repartidor: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("Hubo un error al asignar el repartidor. Verifique la consola para más detalles.");
                    });
                }
            });
        }
    });

    // Guardar el nombre del repartidor en una variable
    let repartidorAsignado = repartidorNombre;
    console.log(repartidorAsignado);
}
</script>
    </body>
</html>
