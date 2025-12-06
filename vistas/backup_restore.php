<?php
// Iniciar sesión y verificar permisos
session_start();
if (!isset($_SESSION['consultav'])) {
  header('Location: login.php'); // Redirigir si no tiene permisos
  exit;
}

include 'header.php'; // Incluir el header común (solo una vez)
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Base de Datos</title>
  <!-- Cargar la fuente Poppins desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Cargar FontAwesome para íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Estilos generales */
    body {
      background-color: #f4f6f9;
      font-family: 'Poppins', sans-serif;
    }

    .container {
      max-width: 2000px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: black;
      text-align: center;
      margin-bottom: 30px;
      font-family: 'Poppins', sans-serif; /* Asegurar que use Poppins */
      font-weight: 600; /* Negrita */
    }

    h2 {
      color: #444;
      margin-bottom: 15px;
      font-size: 1.5em;
    }

    .btn {
      padding: 10px 60px;
      background-color: #6e15c2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: 600;
      height: 40px; /* Altura fija para el botón */
    }

    .btn:hover {
      background-color: black;
      color: white;
    }

    .btn-delete {
      background-color: #dc3545 !important;
      color: #dc3545;
      border: none;
      padding: 0;
      cursor: pointer;
      transition: color 0.3s ease;
      margin-left: 5px; /* Espacio entre los botones */
    }

    .btn-delete:hover {
      color: white;
    }

    .actions-container {
      display: flex;
      gap: 20px; /* Espacio entre los elementos */
      align-items: flex-start; /* Alinear elementos en la parte superior */
      margin-bottom: 30px;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 8px;
      border: 1px solid #eee;
    }

    .restore-form {
      display: flex;
      gap: 10px; /* Espacio entre el campo de archivo y el botón */
      align-items: center; /* Alinear verticalmente */
      width: 100%; /* Ocupar todo el ancho disponible */
    }

    .file-input {
      flex-grow: 1; /* Hacer que el campo de selección de archivos ocupe el espacio restante */
      margin-bottom: 0; /* Eliminar margen inferior */
    }

    .file-input input[type="file"] {
      display: none; /* Ocultar el input de archivo */
    }

    .file-input label {
      display: inline-block;
      padding: 10px 20px;
      background-color: #6e15c2;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: 100%; /* Ocupar todo el ancho disponible */
      text-align: center; /* Centrar el texto */
      height: 40px; /* Altura fija para el campo de selección de archivos */
      line-height: 20px; /* Centrar el texto verticalmente */
    }

    .file-input label:hover {
      background-color: black;
    }

    .backup-list ul {
      list-style-type: none;
      padding: 0;
    }

    .backup-list li {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .backup-list li:last-child {
      border-bottom: none;
    }

    .backup-list li span {
      font-weight: bold;
    }

    .backup-list li .actions {
      display: flex;
      gap: 10px; /* Espacio entre los botones */
      align-items: center;
    }

    .backup-list li button {
      background-color: #6e15c2;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .backup-list li button:hover {
      background-color: black;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Contenido principal -->
    <div class="content-wrapper">
      <section class="content">
        <div class="container">
          <h1>Base de Datos <i class="fa-solid fa-database"></i></h1>

          <!-- Contenedor adicional para las acciones -->
          <div class="actions-container">
            <!-- Botón de Generar Respaldo -->
            <div>
              <h2>Respaldo de Base de Datos</h2>
              <button id="backupBtn" class="btn">Generar Respaldo</button>
              <div id="backupMessage" class="message"></div>
            </div>

            <!-- Formulario de Restauración -->
            <div style="flex-grow: 1;">
              <h2>Restauración de la Base de Datos</h2>
              <form id="restoreForm" method="POST" enctype="multipart/form-data" class="restore-form">
                <div class="file-input">
                  <label for="restoreFile">Seleccionar archivo de respaldo</label>
                  <!-- Elimina el atributo required para que la validación se maneje con SweetAlert -->
                  <input type="file" id="restoreFile" name="sql_file" accept=".sql">
                </div>
                <button type="submit" class="btn">Restaurar</button>
              </form>
              <div id="restoreMessage" class="message"></div>
            </div>
          </div>

          <!-- Lista de Respaldos -->
          <div class="backup-list">
            <h2>Respaldos Disponibles</h2>
            <ul id="backupList">
              <!-- Aquí se cargarán los archivos de respaldo -->
            </ul>
          </div>
        </div>
      </section>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../assets/jquery/jquery.min.js"></script>
  <script src="../public/js/bootstrap.min.js"></script>
  <script src="../public/js/adminlte.min.js"></script>
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script>
    $(document).ready(function() {
      // Inicializar el menú desplegable
      $('[data-toggle="offcanvas"]').on('click', function() {
        $('.sidebar-mini').toggleClass('sidebar-collapse');
      });

      // Función para generar respaldo
      $('#backupBtn').on('click', function() {
        Swal.fire({
          title: 'Generando respaldo...',
          text: 'Por favor espera.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
          url: 'backup.php',
          type: 'POST',
          dataType: 'json',
          success: function(response) {
            Swal.close();
            if (response.success) {
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 3000,
              });
              loadBackups(); // Recargar la lista de respaldos
            } else {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: response.message,
                showConfirmButton: false,
                timer: 3000,
              });
            }
          },
          error: function() {
            Swal.close();
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Error al generar el respaldo.',
              showConfirmButton: false,
              timer: 3000,
            });
          }
        });
      });

      // Función para restaurar la base de datos
      $('#restoreForm').on('submit', function (e) {
        e.preventDefault(); // Evitar el envío del formulario

        const fileInput = $('#restoreFile')[0]; // Obtener el input de archivo
        const file = fileInput.files[0]; // Obtener el archivo seleccionado

        // Validar que se haya seleccionado un archivo
        if (!file) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Es Necesario seleccionar un respaldo para poder restaurar la Base de datos.',
            showConfirmButton: false,
            timer: 6000,
          });
          return;
        }

        // Mostrar mensaje de carga
        Swal.fire({
          title: 'Restaurando base de datos...',
          text: 'Por favor espera.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Crear FormData y agregar el archivo
        const formData = new FormData();
        formData.append('sql_file', file); // Usar el nombre correcto ('sql_file')

        // Enviar el archivo al servidor
        $.ajax({
          url: './restore.php', // Ruta al script PHP que restaurará la BD
          type: 'POST',
          data: formData,
          processData: false, // No procesar los datos
          contentType: false, // No establecer el tipo de contenido
          dataType: 'json', // Esperar una respuesta JSON
          success: function (response) {
            Swal.close(); // Cerrar el mensaje de carga

            if (response.status === 'success') {
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 3000,
              });
            } else {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: response.message,
                showConfirmButton: false,
                timer: 3000,
              });
            }
          },
          error: function (xhr, status, error) {
            Swal.close(); // Cerrar el mensaje de carga

            let errorMessage = 'Error al restaurar la base de datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
              position: 'center',
              icon: 'error',
              title: errorMessage,
              showConfirmButton: false,
              timer: 3000,
            });
          }
        });
      });

      // Función para cargar la lista de respaldos
      function loadBackups() {
        $.ajax({
          url: 'get_backups.php', // Archivo que lista los respaldos
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              $('#backupList').empty(); // Limpiar la lista actual
              response.files.forEach(function(file) {
                // Reemplazar guiones por barras en el nombre del archivo
                const formattedFileName = file.replace(/-/g, '/');
                $('#backupList').append(`
                  <li>
                    <span>${formattedFileName}</span>
                    <div class="actions">
                      <button onclick="downloadBackup('${file}')">Descargar</button>
                      <button onclick="deleteBackup('${file}')" class="btn-delete">
                        <i class="fas fa-trash"></i> <!-- Ícono de basura -->
                      </button>
                    </div>
                  </li>
                `);
              });
            }
          },
          error: function() {
            $('#backupList').html('<li>Error al cargar los respaldos.</li>');
          }
        });
      }

      // Función para descargar un respaldo
      window.downloadBackup = function(filename) {
        window.location.href = `backup/${filename}`;
      };

      // Función para eliminar un respaldo
      window.deleteBackup = function(filename) {
        Swal.fire({
          title: '¿Estás seguro?',
          text: "¡No podrás revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Sí, borrar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: 'delete_backup.php', // Archivo PHP que maneja la eliminación
              type: 'POST',
              data: { filename: filename },
              dataType: 'json',
              success: function(response) {
                if (response.success) {
                  Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000,
                  });
                  loadBackups(); // Recargar la lista de respaldos
                } else {
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000,
                  });
                }
              },
              error: function() {
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Error al intentar borrar el respaldo.',
                  showConfirmButton: false,
                  timer: 3000,
                });
              }
            });
          }
        });
      };

      // Cargar la lista de respaldos al iniciar la página
      loadBackups();
    });
  </script>
</body>
</html>
<?php
require_once "footer.php";
?>