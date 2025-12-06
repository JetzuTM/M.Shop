<?php
// Iniciar sesión y verificar permisos
if (strlen(session_id()) < 1) {
    session_start();
}
if (!isset($_SESSION['nombre']) || $_SESSION['consultav'] != 1) {
    echo "<script>
            Swal.fire({
              title: 'Error',
              text: 'Acceso denegado. No tienes permisos para ver la bitácora.',
              icon: 'error',
              confirmButtonText: 'Aceptar'
            }).then(function() {
                window.location = '../index.php'; // Redirigir si no tiene permisos
            });
          </script>";
    exit;
}

// Funciones propias o inclúyelas desde un archivo común
if (!function_exists('registrarEnBitacora')) {
    function registrarEnBitacora($mensaje) {
        $archivoBitacora = '../logs/bitacora.log';
        if (!is_dir('../logs')) {
            mkdir('../logs', 0777, true);
        }
        $registro = '[' . date('Y-m-d H:i:s') . '] [' . $_SESSION['nombre'] . '] ' . $mensaje . PHP_EOL;
        file_put_contents($archivoBitacora, $registro, FILE_APPEND);
    }
}

function borrarBitacora() {
    $archivoBitacora = '../logs/bitacora.log';
    if (file_exists($archivoBitacora)) {
        file_put_contents($archivoBitacora, ''); // Vaciar el archivo
    }
}

// Leer el contenido de la bitácora
$archivoBitacora = '../logs/bitacora.log';
$registros = [];
if (file_exists($archivoBitacora)) {
    $lineas = file($archivoBitacora, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        // Parsear cada línea del archivo de bitácora
        if (preg_match('/\[(.*?)\] \[(.*?)\] (.*)/', $linea, $matches)) {
            $fechaHora = $matches[1];
            $usuario = $matches[2];
            $descripcion = $matches[3];
            $registros[] = [
                'fecha_hora' => $fechaHora,
                'usuario' => $usuario,
                'descripcion' => $descripcion
            ];
        }
    }

    // Ordenar los registros por fecha y hora en orden descendente
    usort($registros, function($a, $b) {
        return strtotime($b['fecha_hora']) - strtotime($a['fecha_hora']);
    });
}

// Borrar la bitácora si se solicita
if (isset($_GET['borrar']) && $_GET['borrar'] === 'true') {
    borrarBitacora();
    header("Location: bitacora.php?borrado=true");
    exit;
}

// Incluir el header para cargar la estructura y menú
require_once "header.php";
?>

<!--Contenido-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
      <div class="row">
        <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                    <h1 class="box-title">Bitácora del Sistema</h1>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                  <table id="tablaBitacora" class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>Usuario</th>
                              <th>Descripción</th>
                              <th>Fecha y Hora</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($registros as $registro): ?>
                              <tr>
                                  <td><?php echo htmlspecialchars($registro['usuario']); ?></td>
                                  <td><?php echo htmlspecialchars($registro['descripcion']); ?></td>
                                  <td><?php echo htmlspecialchars($registro['fecha_hora']); ?></td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
              <!--Fin centro -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTables
    $('#tablaBitacora').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción al español
        },
        "order": [[2, "desc"]] // Ordenar por fecha y hora descendente
    });
  });
</script>

<?php
require_once "footer.php";
?>