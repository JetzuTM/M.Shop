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
            <h1 class="box-title">Articulo 
              <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                <i class="fa fa-plus-circle"></i> 
                Agregar
              </button>
              <a target="_blank" href="../reportes/rptarticulos.php">
                <button class="btn btn-info">Reporte</button>
              </a>
            </h1>
            <div class="box-tools pull-right">
            </div>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div class="panel-body table-responsive" id="listadoregistros">
            <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Precio de Compra</th>
                <th>Precio de Venta</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Opciones</th>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>
          <div class="panel-body" id="formularioregistros">
            <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre(*):</label>
                <input type="hidden" name="idarticulo" id="idarticulo">
                <input type="text" class="form-control" name="nombre" id="nombre" 
                       maxlength="20" placeholder="Nombre" required
                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,20}"
                       title="Solo letras y espacios (2-20 caracteres)">
                <div class="invalid-feedback">Solo letras y espacios (2-20 caracteres)</div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Categoria(*):</label>
                <select name="idcategoria" id="idcategoria" data-live-search="true" 
                        class="form-control selectpicker" required>
                  <option value="">Seleccione una categoría</option>
                </select>
                <div class="invalid-feedback">Seleccione una categoría</div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <label>Stock(*):</label>
  <input type="number" class="form-control" name="stock" id="stock" 
         placeholder="Stock" min="1" max="1000" step="1" required
         pattern="[1-9][0-9]{0,2}|1000"
         oninput="validarStock(this)">
  <div class="invalid-feedback">El stock debe ser entre 1 y 1000 (números enteros)</div>
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <label>Precio de Compra(*):</label>
  <input type="number" class="form-control" name="precio_compra" id="precio_compra" 
         placeholder="Precio de Compra" min="0" max="99999.99" step="0.01" required
         oninput="validarPrecio(this); compararPrecios();">
  <div class="invalid-feedback" id="error_precio_compra">(máximo 99999.99)</div>
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <label>Precio de Venta(*):</label>
  <input type="number" class="form-control" name="precio_venta" id="precio_venta" 
         placeholder="Precio de Venta" min="0" max="99999.99" step="0.01" required
         oninput="validarPrecio(this); compararPrecios();">
  <div class="invalid-feedback" id="error_precio_venta">(máximo 99999.99)</div>
</div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion" 
                       maxlength="256" placeholder="Descripción">
                <div class="invalid-feedback">Máximo 256 caracteres</div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" 
                       accept="image/jpeg, image/png, image/gif">
                <input type="hidden" class="form-control" name="imagenactual" id="imagenactual">
                <img src="" width="150px" height="120px" id="imagenmuestra">
                <div class="invalid-feedback">Solo imágenes JPG, PNG o GIF (max 2MB)</div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Codigo:</label>
                <input type="text" class="form-control" name="codigo" id="codigo" 
                       placeholder="Codigo de barras" maxlength="50">
                <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
                <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                <div id="print">
                  <svg id="barcode"></svg>
                </div>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar">
                  <i class="fa fa-save"></i> Guardar
                </button>
                <button class="btn btn-danger" onclick="cancelarform()" type="button">
                  <i class="fa fa-arrow-circle-left"></i> Cancelar
                </button>
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
// Validaciones en tiempo real
document.addEventListener('DOMContentLoaded', function() {
  // Validación del nombre
  document.getElementById('nombre').addEventListener('input', function() {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    const valido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,100}$/.test(this.value);
    this.classList.toggle('is-invalid', !valido);
  });

// Función para validar stock (1-1000)
function validarStock(input) {
  const valor = parseInt(input.value);
  const valido = !isNaN(valor) && valor >= 1 && valor <= 1000;
  
  input.classList.toggle('is-invalid', !valido);
  document.getElementById('btnGuardar').disabled = !valido;
  
  // Corregir automáticamente valores fuera de rango
  if (valor < 1) {
    input.value = 1;
  } else if (valor > 1000) {
    input.value = 1000;
  }
}
function validarPrecio(input) {
  const max = 99999.99;
  
  if (parseFloat(input.value) > max) {
    input.value = max.toFixed(2);
  }

  // Opcional: evitar más de 2 decimales
  const match = input.value.match(/^\d+(\.\d{0,2})?/);
  if (match) {
    input.value = match[0];
  }
}

// Función para validar precios (max 7 caracteres)
function validarPrecio(input, maxCaracteres) {
  // Eliminar caracteres adicionales si se excede el límite
  if (input.value.length > maxCaracteres) {
    input.value = input.value.slice(0, maxCaracteres);
  }
  
  // Validar que sea un número válido
  const valor = parseFloat(input.value);
  const valido = !isNaN(valor) && valor >= 0 && input.value.length <= maxCaracteres;
  
  input.classList.toggle('is-invalid', !valido);
  document.getElementById('btnGuardar').disabled = !valido;
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
<script src="../public/js/JsBarcode.all.min.js"></script>
<script src="../public/js/jquery.PrintArea.js"></script>
<script src="./scripts/articulo.js"></script>

<?php
}
ob_end_flush(); //liberar el espacio del buffer
?>