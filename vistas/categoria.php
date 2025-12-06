<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  //iniciamos las variables de session
  session_start();

  if(!isset($_SESSION["nombre"]))
  {
    header("Location: login.html");
  }

  else  //Agrega toda la vista
  {
    require 'header.php';

    if($_SESSION['almacen'] == 1)
    {  
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
                          <h1 class="box-title">Categoria <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                          <a target="_blank" href="../reportes/rptcategoria.php">
                                <button class="btn btn-info">Reporte</button>
                              </a>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>                            
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tfoot>
                        </table>


                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Nombre:</label>
    <input type="hidden" name="idcategoria" id="idcategoria">
    <input type="text" class="form-control" name="nombre" id="nombre" 
           maxlength="20" placeholder="Nombre" required
           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
           title="Solo se permiten letras (no números ni caracteres especiales)">
    <div class="invalid-feedback">Solo se permiten letras (A-Z, a-z) y espacios</div>
</div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

<script>
  // Validación en tiempo real del nombre
document.getElementById('nombre').addEventListener('input', function() {
    // Eliminar números y caracteres especiales
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    
    // Validar que solo contenga letras
    const valido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(this.value);
    this.classList.toggle('is-invalid', !valido);
});

// Validación al enviar el formulario
document.getElementById('tuFormulario').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value;
    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) {
        e.preventDefault();
        document.getElementById('nombre').classList.add('is-invalid');
        document.getElementById('nombre').focus();
    }
});
 </script>

<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }

  require 'footer.php';
?>

<script src="./scripts/categoria.js"></script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>