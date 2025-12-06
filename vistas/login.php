<?php
session_start();

$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$correo_usuario = isset($_SESSION['correo']) ? $_SESSION['correo'] : null;
// Ahora puedes usar $id_del_usuario, $nombre_usuario y $correo_usuario seguramente
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Inicio M.shop</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="../public/img/Multi.png">
  <link rel="shortcut icon" href="../public/img/Multi.ico">

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css"> 
  <link rel="stylesheet" href="../css/tienda_estilo.css">

</head>
<body>
<header id="header" class="header fixed-top">
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
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
    <a class="nav-link  active" href='login.php'>Inicio</a>
</li>
                <li class="nav-item">
                    <a class="nav-link" href="escritorio_tienda.php">Productos</a>
                    <li class="nav-item">
    <a class="nav-link" href="#" id="compras-link">Compras</a></li>
                <!-- Ícono del carrito -->
                <li class="nav-item">
    <a class="nav-link" href="#" id="carrito-link">
        <i class="fas fa-shopping-cart cart-icon"></i>
        <span id="cart-count" class="badge bg-danger"></span>
    </a><li class="nav-item position-relative">
                    <a class="nav-link" href="#" id="notificationBell">
    <i class="fa-solid fa-bell cart-icon"></i>
    <span id="notificationCount" class="badge bg-danger"></span>
</a>
<div id="notificationDropdown" class="dropdown-menu dropdown-menu-end notification-dropdown">
    <div class="notification-header">
    <h6 style="color: black;">Notificaciones</h6>
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
                    </ul>
                </div>
            </div>
        </nav>
</div>
</header>
<main class="main">
<!-- Hero Section -->
<section id="hero" class="hero section dark-background" style="font-size: 1.2rem;">

  <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    <div class="carousel-item active">
      <img src="assets/img/hero-carousel/hero-carousel-1.jpg" alt="">
      <div class="carousel-container">
        <h2 style="font-size: 3rem;">Bienvenido a MultiShop<br></h2>
        <p style="font-size: 2rem;">Ventas de envoltorios de regalos, Bolsas y cajas de regalos.</p><p></p>
        <a href="inicio.php" class="btn-get-started" style="font-size: 1.25rem; padding: 12px 10px;">Empezar</a>
      </div>
    </div><!-- End Carousel Item -->

    <div class="carousel-item">
      <img src="assets/img/hero-carousel/hero-carousel-2.jpg" alt="">
      <div class="carousel-container">
        <p style="font-size: 3rem;">Disfruta de Nuestro amplio Catalogo</p><p></p>
        <a href="escritorio_tienda.php" class="btn-get-started" style="font-size: 1.25rem; padding: 12px 10px;">ir a Productos</a>
      </div>
    </div><!-- End Carousel Item -->

    <div class="carousel-item">
      <img src="assets/img/hero-carousel/hero-carousel-3.jpg" alt="">
      <div class="carousel-container">
        <p style="font-size: 2.6rem;">Encuentra tus pedidos en nuestro apartado de Compras</p><p></p>
        <a href="compras.php" class="btn-get-started" style="font-size: 1.25rem; padding: 12px 10px;">ir a Compras</a>
      </div>
    </div><!-- End Carousel Item -->

    <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
    </a>

    <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
    </a>
    <ol class="carousel-indicators"></ol>
  </div>
</section><!-- /Hero Section -->

<!-- About Section -->
<section id="about" class="about section">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2 style="font-size: 1.7rem;">Acerca de</h2>
    <p style="font-size: 1.5rem;">Nosotros</p>
  </div>

  <div class="container">
    <div class="row align-items-center">
      <!-- Columna para el texto -->
      <div class="col-lg-8">
        <p style="font-size: 1.2rem;">
          La empresa nace en 2014 por la creación de Yadira García, impulsada por la necesidad de emprender y tener su propio negocio. El 15 de marzo de 2014, nace MANFRA C.A., ubicada en Maracay, en el centro comercial La Estación Central, primer nivel, carreta pk-001. Magnífica C.A. fue creada para impulsar las ventas de envoltorios de regalos, bolsas, cajas de regalos, entre otros artículos.
        </p>
        <ul style="font-size: 1.2rem; margin-top: 20px;">
          <li><i class="bi bi-check2-circle"></i> <span>Actualmente contamos con 2 empleados.</span></li>
          <li><i class="bi bi-check2-circle"></i> <span>Ofrecemos exclusivamente venta y comercialización de productos.</span></li>
          <li><i class="bi bi-check2-circle"></i> <span>Atención al cliente rápida y eficaz.</span></li>
        </ul>
      </div>

      <!-- Columna para la imagen -->
      <div class="col-lg-4">
        <a href="../imagenes/tarjeta15.jpg" data-gallery="portfolio-gallery-app" class="glightbox">
          <img src="../imagenes/manfra.png" class="img-fluid rounded" alt="Imagen de MANFRA C.A.">
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio section">

  <!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <h2 style="font-size: 2rem;">Categorías</h2>
  <p style="font-size: 1.5rem;">Tipos de productos que ofrecemos</p>
</div>

<div class="container">
  <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

    <!-- Portfolio Items -->
    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
      <a href="escritorio_tienda.php?categoria=papeles-regalo" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/papelregalo.jpg" class="img-fluid" alt="Papeles de regalo">
        <div class="portfolio-info">
          <h4>Papeles de regalo</h4>
          <p style="font-size: 1.5rem;">Explora nuestra colección de papeles de regalo.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
      <a href="escritorio_tienda.php?categoria=bolsas-regalos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/regalo.png" class="img-fluid" alt="Bolsas de regalos">
        <div class="portfolio-info">
          <h4>Bolsas de regalos</h4>
          <p style="font-size: 1.5rem;">Encuentra bolsas elegantes para tus regalos.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
      <a href="escritorio_tienda.php?categoria=tarjetas-cumpleanos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/tarjetacumple.png" class="img-fluid" alt="Tarjetas de cumpleaños">
        <div class="portfolio-info">
          <h4>Tarjetas de cumpleaños</h4>
          <p style="font-size: 1.5rem;">Tarjetas únicas para celebrar.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
      <a href="escritorio_tienda.php?categoria=tarjetas-15-anos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/tarjeta15.jpg" class="img-fluid" alt="Tarjetas de 15 años">
        <div class="portfolio-info">
          <h4>Tarjetas de 15 años</h4>
          <p style="font-size: 1.5rem;">Celebra con nuestras tarjetas especiales.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
      <a href="escritorio_tienda.php?categoria=globos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/Oso.png" class="img-fluid" alt="Globos">
        <div class="portfolio-info">
          <h4>Globos</h4>
          <p style="font-size: 1.5rem;">Globos decorativos para cualquier ocasión.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
      <a href="escritorio_tienda.php?categoria=lazos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/lazos.png" class="img-fluid" alt="Lazos">
        <div class="portfolio-info">
          <h4>Lazos</h4>
          <p style="font-size: 1.5rem;">Lazos elegantes para tus regalos.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
      <a href="escritorio_tienda.php?categoria=peluches" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/peluches.png" class="img-fluid" alt="Peluches">
        <div class="portfolio-info">
          <h4>Peluches</h4>
          <p style="font-size: 1.5rem;">Peluches tiernos y divertidos.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
      <a href="escritorio_tienda.php?categoria=bisuteria" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/bisuteria.png" class="img-fluid" alt="Bisutería">
        <div class="portfolio-info">
          <h4>Bisutería</h4>
          <p style="font-size: 1.5rem;">Accesorios únicos y elegantes.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
      <a href="escritorio_tienda.php?categoria=arreglos" class="portfolio-content h-100 text-decoration-none">
        <img src="../imagenes/Arreglo.png" class="img-fluid" alt="Arreglos">
        <div class="portfolio-info">
          <h4>Arreglos</h4>
          <p style="font-size: 1.5rem;">Arreglos decorativos para ocasiones especiales.</p>
        </div>
      </a>
    </div><!-- End Portfolio Item -->

  </div><!-- End Portfolio Container -->
</div>
    </div><!-- End Portfolio Container -->
  </div>
</div>
</section><!-- /Portfolio Section -->
        <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2 style="font-size: 2rem;">Contáctanos </h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Dirección</h3>
                <p>Primer nivel carreta pk-001 magnífica c.a</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Llámanos al</h3>
                <p>+58 424-3439084</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Envíenos un correo electrónico</h3>
                <p>manfraca@gmail.com</p>
              </div>
            </div><!-- End Info Item -->
          </div>

          <div class="col-lg-8">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Nombre" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Correo" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Asunto" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Mensaje" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Cargando</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Tú mensaje se ha enviado. ¡Gracias!</div>
                  <button type="submit">Enviar mensaje</button>
                </div>
              </div>
            </form>

  </main>
  <footer id="footer" class="footer dark-background">
    <div class="container">
    <div class="copyright">
      <div class="social-links d-flex justify-content-center">
        <a href=""><i class="bi bi-facebook"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-linkedin"></i></a>
      </div>
      <div class="container">
        <div class="copyright">
          <span>Copyright © Todos los derechos reservados by </span> <strong class="px-1 sitename">MANFRA C.A.</strong>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

<script>
src="../public/bootstrap/js/bootstrap.min.js"></script>
        <script>
        function estaAutenticado() {
    return Boolean(<?php echo isset($_SESSION['idusuario']) ? 'true' : 'false'; ?>);
}
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
<script>
    function updateCartCountFromStorage() {
        const cartFromStorage = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cartFromStorage.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cart-count').textContent = totalItems;
    }

    document.addEventListener('DOMContentLoaded', updateCartCountFromStorage);
</script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</script>
</script>
</body>
</html>