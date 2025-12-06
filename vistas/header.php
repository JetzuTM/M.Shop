<?php
// INICIAR SESIÓN
if (strlen(session_id()) < 1) {
    session_start();
}
// CONFIGURACIÓN PARA BITÁCORA (LÓGICA DEL HEADER ACTUAL)
$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "dbsistema";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | MultiShop</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">

    <!--DATATABLES-->
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">

    <link rel="stylesheet" href="../public/css/bootstrap-select.min.css">

    <!-- SweetAlert2 -->
    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">

    <!-- jQuery -->
    <script src="../assets/jquery/jquery.min.js"></script>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
<!-- Incluye Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Estilos CSS adicionales -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f6f9;
  }

  .sidebar-menu > li > a > i {
    width: 20px;
    margin-right: 10px;
    text-align: center;
  }

  /* Estilos para el logo en el header */
.navbar-brand {
padding: 0; /* Eliminar padding predeterminado */
margin: 0; /* Eliminar margen predeterminado */
display: flex;
align-items: center; /* Centrar verticalmente el logo y el texto */
}

.navbar-brand img {
height: 35px; /* Ajusta el tamaño del logo */
margin-right: 15px; /* Espacio entre el logo y el texto */
margin-left: 8px;
}

/* Eliminar el margen izquierdo del header */
.main-header {
margin-left: 0; /* Asegurarse de que no haya margen */
padding-left: 0; /* Asegurarse de que no haya padding */
}
/* Mostrar submenús al pasar el cursor sobre los íconos */
.sidebar-menu > li.treeview:hover > .treeview-menu {
display: block;
}

/* Estilos para los íconos */
.sidebar-menu > li.treeview > a > i {
cursor: pointer; /* Cambiar el cursor a pointer */
}

/* Asegurar que los submenús estén ocultos por defecto */
.treeview-menu {
display: none;
}

/* Mostrar submenús cuando el elemento padre tiene la clase 'active' */
.treeview.active .treeview-menu {
display: block;
}
/* Mostrar submenús cuando el elemento padre tiene la clase 'active' */
.treeview-menu {
display: none;
}

.treeview.active > .treeview-menu {
display: block;
}

/* Animación para el ícono de flecha */
.sidebar-menu > li.treeview > a > .fa-angle-left {
transition: transform 0.3s ease;
}

.sidebar-menu > li.treeview.active > a > .fa-angle-left {
transform: rotate(-90deg);
}
</style>
    <header class="main-header" style="background-color: #6e15c2; color: white;">
        <a href="#" class="navbar-brand" style="color: white; font-size: 24px; font-weight: bold;">
            <img src="../public/img/Multi.png" alt="Logo MultiShop">
            MultiShop
        </a>
        <!-- Barra de navegación -->
        <nav class="navbar navbar-static-top" role="navigation" style="background-color: #6e15c2;">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>
        <!-- Menú de usuario a la derecha -->    
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle user-icon" data-toggle="dropdown" style="background: none; border: none;">
                            <i class="fa fa-user fa-2x" style="color: white; margin-right: 10px;"></i>
                            <span class="hidden-xs"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header" style="background-color: #e7e7e7;">
                                <i class="fa fa-user-circle fa-4x" style="color: black;"></i>
                                <p style="color: black;"><?php echo $_SESSION['nombre']; ?></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar sesión</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header"></li>
                <?php
                if ($_SESSION['escritorio'] == 1) {
                    echo
                    '<li>
                  <a href="escritorio.php">
                    <i class="fa fa-tasks"></i> <span>Escritorio</span>
                  </a>
                </li>';
                }

                if ($_SESSION['almacen'] == 1) {
                    echo
                    '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-laptop"></i>
                      <span>Almacen</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                      <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                    </ul>
                  </li>';
                }
                if ($_SESSION['compras'] == 1) {
                    echo
                    '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-th"></i>
                      <span>Compras</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                      <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                    </ul>
                  </li>';
                }
                if ($_SESSION['ventas'] == 1) {
                    echo '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-shopping-cart"></i>
                      <span>Ventas</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
                      <li><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                    </ul>
                  </li>

                  <li class="treeview">
                  <a href="#">
                        <i class="fa-solid fa-boxes-stacked "></i>
                        <span> Pedidos</span>
                        <i class="fa fa-angle-left pull-right"></i>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="pactivos.php"><i class="fa fa-circle-o"></i> Lista de pedidos</a></li>
                      </ul>';
                }

                if ($_SESSION['consultac'] == 1) {
                    echo
                    '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-bar-chart"></i> <span>Consultas</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>
                      <li><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i> Consulta Ventas</a></li>
                    </ul>
                  </li>';
                }


                //Bitacora y Respaldo de Base de Datos
                if ($_SESSION['consultav'] == 1) {
                    echo
                    '<li class="treeview">
                 <a href="#">
                   <i class="fa-solid fa-database"></i> <span>Registro</span>
                   <i class="fa fa-angle-left pull-right"></i>
                 </a>

                 <ul class="treeview-menu">
                   <li><a href="bitacora.php"><i class="fa fa-circle-o"></i> Bitácora</a></li>
                   <li><a href="backup_restore.php"><i class="fa fa-circle-o"></i> Base de Datos </a></li>
                </ul>
              </li>';
                }

                if ($_SESSION['acceso'] == 1) {
                    echo
                    '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-gear"></i> <span>Acceso</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="usuario.php"><i class="fa-solid fa-address-card"></i> Empleados</a></li>
                      <li><a href="delivery.php"><i class="fa-solid fa-motorcycle"></i> Repartidores</a></li>
                      </ul>
                  </li>';
                }
echo
'<li>
    <a href="ayuda.php">
        <i class="fa fa-question-circle"></i> <span>Ayuda</span>
    </a>
</li>';
                ?>
            </ul>

        </section>
        <!-- /.sidebar -->
    </aside>

    <script>
      
  $(document).ready(function() {
    // Restaurar la base de datos
    $('#restoreBDLink').on('click', function(e) {
      e.preventDefault(); // Evitar la navegación

      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción restaurará la base de datos desde el último respaldo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Restaurando...',
            text: 'Por favor espera.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          $.ajax({
            url: 'restore.php', // Ruta al archivo de restauración
            type: 'POST',
            dataType: 'json',
            success: function(response) {
              Swal.close();
              if (response.success) {
                Swal.fire({
                  position: 'bottom-end', // Posición en la parte inferior derecha
                  icon: 'success',
                  title: response.message,
                  showConfirmButton: false,
                  timer: 3000, // Duración en milisegundos (3 segundos)
                  toast: true, // Mostrar como toast (notificación pequeña)
                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.message,
                  showConfirmButton: false,
                  timer: 3000,
                  toast: true,
                });
              }
            },
            error: function() {
              Swal.close();
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: 'Ocurrió un error al intentar restaurar la base de datos.',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
              });
            }
          });
        }
      });
    });


  $(document).ready(function() {
  // Manejar el clic en el ícono de flecha para mostrar/ocultar submenús
  $('.sidebar-menu > li.treeview > a > .fa-angle-left').on('click', function(e) {
    e.preventDefault();
    var parent = $(this).closest('li');
    parent.toggleClass('active');
    parent.find('> .treeview-menu').slideToggle();
  });
});
</script>
</body>
</html>