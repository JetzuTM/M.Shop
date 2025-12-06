<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
?>
<style>
    .video-container {
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background: #fff;
    }
    .video-title {
        background-color: #6e15c2;
        color: white;
        padding: 10px 15px;
        margin: 0;
        font-size: 16px;
    }
    .video-wrapper {
        width: 100%;
        position: relative;
        padding-bottom: 56.25%; /* Relación 16:9 */
        height: 0;
    }
    .video-wrapper video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
    }
    .help-section {
        margin-bottom: 40px;
    }
    .help-section h3 {
        color: #6e15c2;
        border-bottom: 2px solid #6e15c2;
        padding-bottom: 10px;
        margin-top: 30px;
    }
    .video-controls {
        background: #f5f5f5;
        padding: 10px;
        text-align: center;
    }
    .video-controls button {
        margin: 0 5px;
    }
    .empty-video-placeholder {
        background: #f9f9f9;
        border: 2px dashed #ddd;
        padding: 30px;
        text-align: center;
        color: #888;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Ayuda y Tutoriales</h1>
                        <div class="box-tools pull-right"></div>
                    </div>
                    
                    <div class="panel-body" style="padding: 20px;">
                        <!-- Módulo de Registro -->
                        <div class="help-section">
                            <h3><i class="fa fa-laptop"></i> Módulo de Almacén</h3>
                            <div class="row">
                                <!-- Video 1 -->
                                <div class="col-md-6">
                                    <div class="video-container">
                                        <h4 class="video-title">Cómo registrar un artículo</h4>
                                        <div class="video-wrapper">
                                            <video id="video1" controls>
                                            <source src="../public/videos/almacen/registrar_articulo.mp4" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Video 2 (Espacio para nuevo video) -->
                                <div class="col-md-6">
                                    <div class="video-container">
                                        <h4 class="video-title">Registrar Categorías</h4>
                                        <div class="video-wrapper">
                                            <video id="video2" controls>
                                            <source src="../public/videos/almacen/categoria.mp4" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <!-- Módulo de Almacén -->
                        <div class="help-section">
                            <h3><i class="fa fa-database"></i> Módulo de Registro</h3>
                            <div class="row">
                                <!-- Video 3 -->
                                <div class="col-md-6">
                                    <div class="video-container">
                                        <h4 class="video-title">Realizar Respaldos y Restauración de BD</h4>
                                        <div class="video-wrapper">
                                            <video id="video3" controls>
                                            <source src="../public/videos/ventas/registrar_venta.mp4" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                                
                        <!-- Módulo de pedidos -->
                        <div class="help-section">
                            <h3><i class="fa-solid fa-boxes-stacked"></i> Módulo de pedidos</h3>
                            <div class="row">
                                <!-- Video 5 -->
                                <div class="col-md-6">
                                    <div class="video-container">
                                        <h4 class="video-title">Modificar Estado del Pedido</h4>
                                        <div class="video-wrapper">
                                            <video id="video5" controls>
                                                <source src="../public/videos/compras/registrar_ingreso.mp4" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                        <!-- Nuevo Módulo (Ejemplo para añadir más secciones) -->
                        <div class="help-section">
                            <h3><i class="fa fa-users"></i> Módulo de Acceso</h3>
                            <div class="row">
                                <!-- Video 7 (Espacio para nuevo video) -->
                                <div class="col-md-6">
                                    <div class="video-container">
                                        <h4 class="video-title">Administrar Empleados y Repartidores</h4>
                                        <div class="video-wrapper">
                                        <video id="video5" controls>
                                                <source src="../public/videos/compras/delivery.mp4" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Script para controlar los videos
$(document).ready(function() {
    // Opcional: Agregar controles personalizados
    $('video').each(function() {
        var video = $(this)[0];
        var container = $(this).parent().parent(); // Cambiado para apuntar al video-container
        
        // Crear controles personalizados
        var controls = $('<div class="video-controls">' +
                         '<button class="btn btn-xs btn-primary play-btn"><i class="fa fa-play"></i> Play</button>' +
                         '<button class="btn btn-xs btn-warning pause-btn"><i class="fa fa-pause"></i> Pausa</button>' +
                         '<button class="btn btn-xs btn-info restart-btn"><i class="fa fa-redo"></i> Reiniciar</button>' +
                         '</div>');
        
        container.append(controls);
        
        // Asignar eventos a los botones
        container.find('.play-btn').click(function() {
            video.play();
        });
        
        container.find('.pause-btn').click(function() {
            video.pause();
        });
        
        container.find('.restart-btn').click(function() {
            video.currentTime = 0;
            video.play();
        });
    });
    
    // Opcional: Registrar visualización de videos
    $('video').on('play', function() {
        var videoId = $(this).attr('id');
        var videoSrc = $(this).find('source').attr('src');
        
        $.post('ajax/ayuda.php?op=registrar_visto', {
            video_id: videoId,
            video_src: videoSrc,
            idusuario: <?php echo $_SESSION['idusuario']; ?>
        });
    });
});
</script>

<?php
    require 'footer.php';
}
ob_end_flush();
?>