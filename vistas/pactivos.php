<?php
// Activación de almacenamiento en buffer
ob_start();
// Iniciamos las variables de sesión
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    require_once "../config/conexion.php";

    if ($_SESSION['almacen'] == 1) {
        // Consulta para obtener todos los pedidos, incluyendo el nombre del repartidor
        $sql = "SELECT 
                    p.idpedido, 
                    p.fecha, 
                    p.estado, 
                    p.entrega, 
                    s.nombre AS nombre_usuario, 
                    d.nombre AS nombre_delivery
                FROM 
                    pedidos p
                JOIN 
                    suscripciones s ON p.idusuario = s.id
                LEFT JOIN 
                    delivery d ON p.id_delivery = d.iddelivery
                ORDER BY 
                    p.fecha DESC";

        $resultado = mysqli_query($conexion, $sql);

?>
<style>
/* Estilo del botón Modificar Estados */
.detail {
    background-color: #046fe0; 
    color: white; 
    border: none;
    border-radius: 2px;
    cursor: pointer;
    font-size: 13px;
    padding: 5px 10px; 
    transition: background-color 0.3s ease; 
}

.details-button {
    background-color: #4CAF50; 
    color: white; 
    border: none;
    border-radius: 2px;
    cursor: pointer;
    font-size: 13px;
    padding: 5px 15px; 
    transition: background-color 0.3s ease; 
}

.details-button:hover {
    background-color: #45a049; 
}
.bg-gray-dark {
    background-color: #8c8f8f; /* Este es un ejemplo de un gris oscuro, ajusta el código hex a tu gusto */
}
    #helpButton:hover {
        background: #f5f5f5;
        border-color: #3c8dbc;
        transform: scale(1.05);
    }
    #helpButton:active {
        transform: scale(0.98);
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                <div class="box-header with-border">
    <div style="display: flex; align-items: center;">
        <h1 class="box-title" style="margin-right: 10px;">Pedidos</h1>
    </h1>
    <div class="box-tools pull-right">
    </div>
</div>
                    <div class="panel-body table-responsive" id="listadopedidos">
                        <table id="tbpedidos" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
    <th>ID Pedido</th>
    <th>Fecha y Hora</th>
    <th>Usuario</th>
    <th>Repartidor</th>
    <th>Estado</th>
    <th>Opciones</th>
</thead>
<tbody>
    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
    <tr>
        <td><?php echo $row['idpedido']; ?></td> <!-- ID Pedido -->
        <td><?php echo date("m/d/Y h:i A", strtotime($row['fecha'])); ?></td> <!-- Fecha y Hora -->
        <td><?php echo $row['nombre_usuario']; ?></td> <!-- Usuario -->
        <td>
            <?php
            $deliveryColor = empty($row['nombre_delivery']) ? 'label bg-gray-dark' : 'blue';
            ?>
            <span class="label bg-<?php echo $deliveryColor; ?>">
                <?php echo empty($row['nombre_delivery']) ? 'No Asignado' : $row['nombre_delivery']; ?>
            </span>
        </td> <!-- Repartidor -->
        <td>
            <?php
            $estadoColor = '';
            switch($row['estado']) {
                case 'Aceptado':
                    $estadoColor = 'green';
                    break;
                case 'Rechazado':
                    $estadoColor = 'red';
                    break;
                case 'Finalizado':
                    $estadoColor = 'orange';
                    break;
                default:
                    $estadoColor = 'label bg-gray-dark';
            }
            ?>
            <span class="label bg-<?php echo $estadoColor; ?>"><?php echo $row['estado']; ?></span>
        </td> <!-- Estado -->
        <td>
            <button class="details-button" onclick="cambiarEstado(<?php echo $row['idpedido']; ?>)">Cambiar Estado</button>
            <button class="btn btn-primary descargar-pdf-btn detail" data-pedido-id="<?php echo $row['idpedido']; ?>"><i class="fa-solid fa-file-pdf"></i></button>
            <button class="btn btn-warning fa fa-eye" onclick="verDetalles(<?php echo $row['idpedido']; ?>)"></button>
        </td> <!-- Opciones -->
    </tr>
    <?php endwhile; ?>
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal para detalles del pedido (sin cambios) -->
<div id="customModal" style="display: none; position: fixed; z-index: 9999; right: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 800px;">
        <span id="closeModal" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <div id="modalContent"></div>
    </div>
</div>

<script>
function verDetalles(idpedido) {
    $.ajax({
        url: 'obtener_detalles_pedido.php',
        type: 'POST',
        data: { idpedido: idpedido },
        success: function(response) {
            var detalles = JSON.parse(response);
            
            var modalContent = `
                <h4 style="margin-bottom: 20px;">Detalles del Pedido #${idpedido}</h4>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Imagen</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Artículo</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Cantidad</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Precio Unitario</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            var total = 0;
            detalles.forEach(function(item) {
                var subtotal = item.cantidad * item.precio;
                total += subtotal;
                modalContent += `
                    <tr>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                            <img src="../files/articulos/${item.imagen}" alt="${item.nombre_articulo}" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">${item.nombre_articulo}</td>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">${item.cantidad}</td>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Bs ${parseFloat(item.precio).toFixed(2)}</td>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Bs ${subtotal.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            modalContent += `
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f2f2f2;">
                                <th colspan="4" style="padding: 12px; text-align: right; border-bottom: 1px solid #ddd;">Total:</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Bs ${total.toFixed(2)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            
            document.getElementById('modalContent').innerHTML = modalContent;
            document.getElementById('customModal').style.display = 'block';
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Error al obtener los detalles del pedido.',
                icon: 'error'
            });
        }
    });
}

// Cerrar el modal
document.getElementById('closeModal').onclick = function() {
    document.getElementById('customModal').style.display = 'none';
}

// Cerrar el modal si se hace clic fuera de él
window.onclick = function(event) {
    var modal = document.getElementById('customModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php
  } else {  
 require 'noacceso.php';
  }
 require 'footer.php';
?>

<script src="../public/js/jquery.PrintArea.js"></script>
<script>
$(document).ready(function() {
    $('#tbpedidos').DataTable({
        "language": {
            "lengthMenu": "Mostrar _MENU_ entradas", // Cambia "Show entries" a "Mostrar entradas"
            "search": "Buscar:", // Cambia "Search" a "Buscar"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas", // Cambia el texto de información
            "paginate": {
                "first": "Primero", // Cambia "First" a "Primero"
                "last": "Último", // Cambia "Last" a "Último"
                "next": "Siguiente", // Cambia "Next" a "Siguiente"
                "previous": "Anterior" // Cambia "Previous" a "Anterior"
            }
        },
        "order": [[1, "desc"]] // Ordenar por la segunda columna (fecha) en orden descendente
    });
});

function cambiarEstado(idpedido) {
    // Obtener el estado actual del pedido
    var estadoActual = $('#tbpedidos tr').filter(function() {
        return $(this).find('td:first').text() == idpedido;
    }).find('td:nth-child(5)').text().trim(); // Cambiado de 4 a 5

    Swal.fire({
        title: 'Selecciona un estado',
        input: 'select',
        inputOptions: {
            'Aceptado': 'Aceptado',
            'Rechazado': 'Rechazado',
            'Finalizado': 'Finalizado'
        },
        inputPlaceholder: 'Selecciona un estado',
        showCancelButton: true,
        confirmButtonText: 'Siguiente',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const nuevoEstado = result.value;

            // Verificar si el nuevo estado es igual al estado actual
            if (nuevoEstado === estadoActual) {
                Swal.fire({
                    title: 'Error',
                    text: 'No puedes seleccionar el mismo estado que el estado actual del pedido.',
                    icon: 'error'
                });
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Quieres cambiar el estado del pedido a ${nuevoEstado}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar estado',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('cambiar_estado_pedido.php', { idpedido: idpedido, estado: nuevoEstado }, function(response) {
                        Swal.fire({
                            title: '¡Actualizado!',
                            text: 'El estado del pedido ha sido actualizado.',
                            icon: 'success',
                            timer: 2000
                        });
                        // Actualizar la celda correspondiente en la tabla (cambiado de 4 a 5)
                        $('#tbpedidos tr').each(function() {
                            var id = $(this).find('td:first').text();
                            if (id == idpedido) {
                                $(this).find('td:nth-child(5)').html(getEstadoHTML(nuevoEstado));
                            }
                        });
                    }).fail(function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cambiar el estado del pedido.',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    });
}

function getEstadoHTML(estado) {
    var color = '';
    estado = estado.trim(); // Eliminar espacios en blanco
    switch(estado) {
        case 'Aceptado':
            color = 'green';
            break;
        case 'Rechazado':
            color = 'red';
            break;
        case 'Finalizado':
            color = 'orange';
            break;
        default:
            color = 'label bg-gray-dark';
    }
    return `<span class="label bg-${color}">${estado}</span>`;
}

// Asegúrate de que el modal se muestre correctamente
$(document).ready(function() {
    $('#modalDetalles').on('shown.bs.modal', function () {
        $(this).find('.modal-body').css({
            'max-height': '60vh',
            'overflow-y': 'auto'
        });
    });

    // Agregar manejador para cerrar el modal
    $(document).on('click', '.modal-backdrop, .modal .close, .modal .btn-secondary', function() {
        $('#modalDetalles').modal('hide');
    });
});
const descargarPdfBtns = document.querySelectorAll('.descargar-pdf-btn');

descargarPdfBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const pedidoId = this.getAttribute('data-pedido-id');

        // Crear un formulario oculto
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'generar_pdf.php';
        form.target = '_blank'; // Abrir en una nueva pestaña

        // Crear un campo oculto para el ID del pedido
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'pedido_id';
        input.value = pedidoId;

        // Agregar el campo al formulario
        form.appendChild(input);

        // Agregar el formulario al cuerpo del documento y enviarlo
        document.body.appendChild(form);
        form.submit();

        // Eliminar el formulario después de enviarlo
        document.body.removeChild(form);
    });
     // Manejar clic en el botón de ayuda
    $('#helpButton').click(function() {
        Swal.fire({
            title: 'Ayuda sobre Pedidos',
            html: '<b>Información sobre la gestión de pedidos:</b><br><br>' +
                  '• Puedes buscar cualquier pedido usando la barra de búsqueda<br>' +
                  '• Usa los botones en la columna "Opciones" para:<br>' +
                  '&nbsp;&nbsp;- Cambiar el estado del pedido<br>' +
                  '&nbsp;&nbsp;- Descargar el PDF del pedido<br>' +
                  '&nbsp;&nbsp;- Ver los detalles completos',
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
});
});
</script>
<?php
}
ob_end_flush();
?>