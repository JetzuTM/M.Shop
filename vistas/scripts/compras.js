//Boton PDF
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
});
//Boton PDF
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//Pedidos btns
document.addEventListener('DOMContentLoaded', function() {
    const viewGuideBtns = document.querySelectorAll('.view-guide-btn');
    viewGuideBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            const modal = document.getElementById('guideModal');
            modal.style.display = 'block';
        });
    });

    const closeBtn = document.getElementsByClassName('close')[0];
    closeBtn.addEventListener('click', function() {
        const modal = document.getElementById('guideModal');
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('guideModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
const viewOrderBtns = document.querySelectorAll('.view-order-btn');
viewOrderBtns.forEach(btn => {
    const pedidoId = btn.getAttribute('data-pedido-id');
    const eyeIcon = document.getElementById(`eye-icon-${pedidoId}`);

    btn.addEventListener('click', function() {
        const detallesContainer = document.getElementById(`pedido-${pedidoId}`);
        if (detallesContainer.style.display === 'none') {
            detallesContainer.style.display = 'block';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            detallesContainer.style.display = 'none';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
});

// Funcionalidad para calificar productos (botón general)
const calificarBtns = document.querySelectorAll('.calificar-btn');

calificarBtns.forEach(btn => {
btn.addEventListener('click', async function() {
const pedidoId = this.getAttribute('data-pedido-id');

const { value: calificacion } = await Swal.fire({
    title: 'Califica tu pedido',
    html: `
        <div class="star-rating">
            <span class="star" data-value="1">&#9867;</span>
            <span class="star" data-value="2">&#9867;</span>
            <span class="star" data-value="3">&#9867;</span>
            <span class="star" data-value="4">&#9867;</span>
            <span class="star" data-value="5">&#9867;</span>
        </div>
    `,
    preConfirm: () => {
        const stars = document.querySelectorAll('.star');
        let selectedRating = 0;
        stars.forEach(star => {
            if (star.classList.contains('selected')) {
                selectedRating = parseInt(star.getAttribute('data-value'));
            }
        });
        return selectedRating;
    },
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Siguiente',
    didOpen: () => {
        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-value'));
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-value')) <= rating) {
                        s.classList.add('selected');
                        s.innerHTML = '&#9733;'; // Estrella llena
                    } else {
                        s.classList.remove('selected');
                        s.innerHTML = '&#9867;'; // Estrella vacía
                    }
                });
            });
            star.addEventListener('mouseover', function() {
                const rating = parseInt(this.getAttribute('data-value'));
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-value')) <= rating) {
                        s.innerHTML = '&#9733;'; // Estrella llena (hover)
                    }
                });
            });
            star.addEventListener('mouseout', function () {
                const stars = document.querySelectorAll('.star');
                stars.forEach(s => {
                    if (!s.classList.contains('selected')) {
                        s.innerHTML = '&#9867;'; // Estrella vacía (mouseout)
                    }
                });
            });
        });
    }
});



if (calificacion) {
    const { value: text } = await Swal.fire({
        title: '¿Qué te pareció el pedido?',
        input: "textarea",
        inputLabel: "Cuentanos tu experiencia",
        inputPlaceholder: "Escribe tu opinión aquí...",
        inputAttributes: {
            "aria-label": "Escribe tu opinión aquí"
        },
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Enviar'
    });

    if (text) {
        Swal.fire({
            title: '¡Gracias por tu calificación!',
            text: `Calificación: ${calificacion} estrellas\nComentario: ${text}`, // Mostrar la calificación
            icon: 'success'
        });
         // Aquí puedes enviar la calificación (calificacion) y el texto al servidor
         fetch('/guardar_calificacion', {  // Reemplaza '/guardar_calificacion' con la ruta de tu servidor
method: 'POST',
headers: {
'Content-Type': 'application/json'
},
body: JSON.stringify({
pedidoId: pedidoId,
calificacion: calificacion,
comentario: text
})
})
.then(response => { /* Manejar la respuesta del servidor */ })
.catch(error => { /* Manejar errores */ });
    }
}
});
});

// Nueva funcionalidad para calificar productos individuales
const calificarProductoBtns = document.querySelectorAll('.calificar-producto-btn');
calificarProductoBtns.forEach(btn => {
    btn.addEventListener('click', async function() {
        const productoNombre = this.getAttribute('data-producto-nombre');
        const productoImagen = this.getAttribute('data-producto-imagen');

        const { value: text } = await Swal.fire({
            title: `${productoNombre}`,
            html: `
                <div class="mb-3">
                    <img src="${productoImagen}" style="max-width: 180px; max-height: 180px; margin-bottom: 15px;">
                </div>
                <textarea id="swal-input1" class="swal2-textarea" placeholder="Escribe tu opinión aquí..."></textarea>
            `,
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Enviar',
            preConfirm: () => {
                return document.getElementById('swal-input1').value;
            }
        });

        if (text) {
            Swal.fire({
                title: '¡Gracias por tu calificación!',
                text: 'Tu opinión sobre este producto ha sido registrada.',
                icon: 'success'
            });
        }
    });
});
});

document.addEventListener('DOMContentLoaded', function() {
// Existing functionality...

const viewGuideBtns = document.querySelectorAll('.view-guide-btn');
viewGuideBtns.forEach(btn => {
btn.addEventListener('click', function() {
// Implement logic to show the order tracking information (e.g., modal, new section) based on the order ID.
// This section is currently not implemented. 
console.log('Ver Guia clicked for order:', this.getAttribute('data-pedido-id'));
});
});
});
document.addEventListener('DOMContentLoaded', function() {
    const viewGuideBtns = document.querySelectorAll('.view-guide-btn');
    viewGuideBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            const estadoPedido = obtenerEstadoPedido(pedidoId); // Asegúrate de implementar esta función

            Swal.fire({
                title: 'Seguimiento del Pedido N° ' + pedidoId,
                html: `
                    <div class="row">
                        <div class="col-md-4">
                            <div class="status-box ${estadoPedido === 'almacen' ? 'active' : ''}">
                                <h5>En Almacén</h5>
                                <p>El pedido está siendo preparado.</p>
                            </div>
                            <div class="circle ${estadoPedido === 'almacen' ? 'active' : ''}"></div>
                            <div class="line right ${estadoPedido === 'almacen' || estadoPedido === 'camino' ? 'active' : ''}"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-box ${estadoPedido === 'camino' ? 'active' : ''}">
                                <h5>En Camino</h5>
                                <p>El pedido está siendo entregado.</p>
                            </div>
                            <div class="circle ${estadoPedido === 'camino' ? 'active' : ''}"></div>
                            <div class="line left ${estadoPedido === 'camino' ? 'active' : ''}"></div>
                            <div class="line right ${estadoPedido === 'camino' ? 'active' : ''}"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-box ${estadoPedido === 'entregado' ? 'active' : ''}">
                                <h5>Entregado</h5>
                                <p>El pedido ya ha llegado a su destino.</p>
                            </div>
                            <div class="circle ${estadoPedido === 'entregado' ? 'active' : ''}"></div>
                            <div class="line left ${estadoPedido === 'entregado' ? 'active' : ''}"></div>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'swal2-custom-popup'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        });
    });
});

function obtenerEstadoPedido(pedidoId) {
    // Aquí deberías implementar la lógica para obtener el estado del pedido.
    // Por ejemplo, podrías hacer una llamada AJAX al servidor para obtener el estado.
    // Este es solo un ejemplo estático:
    const estadosPedidos = {
        '1': 'almacen', // Pedido 1 está en almacén
        '2': 'camino',  // Pedido 2 está en camino
        '3': 'entregado' // Pedido 3 está entregado
    };
    return estadosPedidos[pedidoId] || 'almacen'; // Por defecto, el pedido está en almacén
}

//Pedidos btns    
////////////////////////////////////////////////////////////////
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
        .catch(error => mostrarError('Error al marcar notificaciones como leídas:', error));
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
                    .catch(error => {
                        mostrarError('Error en la solicitud AJAX:', error);
                    });
            })
            .catch(error => {
                mostrarError('Error al marcar notificaciones como leídas:', error);
            });
    }
    // Llama a esta función después de marcar las notificaciones como leídas
    actualizarNotificaciones();
});
