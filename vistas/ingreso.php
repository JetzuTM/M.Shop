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

    if($_SESSION['compras'] == 1)
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
                          <h1 class="box-title">Ingreso <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                           <thead>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero Doc.</th>
                            <th>Total Compra</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                         <!--   <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero Doc.</th>
                            <th>Total Compra</th>
                            <th>Estado</th>-->
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Proveedor(*):</label>
                            <input type="hidden" name="idingreso" id="idcategoria">
                            <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Comprobante(*):</label>
                            <select class="form-control selectpicker" name="tipo_comprobante" id="tipo_comprobante" required>
                              <option value="Factura">Factura</option>
                              
                            </select>
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Numero:</label>
                            <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Numero">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Impuesto (%):</label>
                            <input type="number" class="form-control" name="impuesto" id="impuesto" min="0" max="100" step="0.01" placeholder="0" title="Porcentaje de impuesto a aplicar">
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                              <a data-toggle="modal" href="#myModal" >
                              </a>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="panel panel-info">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Detalle de Artículos</h3>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-3">
                                      <button type="button" id="btnAgregarArt" class="btn btn-primary" data-toggle="modal" href="#myModal">
                                        <span class="fa fa-plus"></span> Agregar Artículos
                                      </button>
                                    </div>
                                    <div class="col-md-9 text-right">
                                      <div id="total" class="alert alert-success" style="display: inline-block; margin: 0; padding: 10px 20px;">
                                        <h4 style="margin: 0;">BsD 0.00</h4>
                                      </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                      <th>Artículo</th>
                                      <th>Cantidad</th>
                                      <th>Precio Compra</th>
                                      <th>Precio Venta</th>
                                    </thead>
                                    <tfoot>
                                      <tr>
                                        <th colspan="3" style="text-align: right;">TOTAL:</th>
                                        <th style="text-align: right;">
                                          <h4 id="total-footer">BsD 0.00</h4>
                                          <input type="hidden" name="total_compra" id="total_compra">
                                        </th>
                                        <th></th>
                                      </tr>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                  </table>
                                </div>
                              </div>
                          </div>
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

  <!--VENTANA MODAL-->
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" style="width: 90%; max-width: 1200px;">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title">Seleccione un articulo</h4>
           </div>

           <div class="modal-body">
             <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
               <thead>
                 <th>Nombre</th>
                 <th>Categoria</th>
                 <th>Codigo</th>
                 <th>Stock</th>
                 <th>Imagen</th>
                 <th>Opciones</th>
               </thead>
               <tbody>

               </tbody>
               <tfoot>
                 <th>Nombre</th>
                 <th>Categoria</th>
                 <th>Codigo</th>
                 <th>Stock</th>
                 <th>Imagen</th>
                 <th>Opciones</th>
               </tfoot>
             </table>
           </div>

           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
           </div>

         </div>
       </div>
     </div>
  <!--FIN VENTANA MODAL-->


<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }

  require 'footer.php';
?>

<script src="./scripts/ingreso.js"></script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>