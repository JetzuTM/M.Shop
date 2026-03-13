var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/venta.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});
	
	// Agregar evento para cálculo automático cuando cambie el impuesto
	$("#impuesto").on("change keyup", function() {
		calcularTotales();
	});
}

//Función limpiar
function limpiar()
{
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("0");

	$("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles=0;
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'frtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/venta.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función ListarArticulos
function listarArticulos()
{
	tabla=$('#tblarticulos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../ajax/venta.php?op=listarArticulosVenta',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	
	// Validar que existan detalles
	if (detalles <= 0) {
		bootbox.alert("⚠️ Debe agregar al menos un artículo a la venta");
		return;
	}
	
	// Validar que todos los campos de detalles tengan valores válidos
	var cantidades = document.getElementsByName("cantidad[]");
	var precios_venta = document.getElementsByName("precio_venta[]");
	
	for (var i = 0; i < cantidades.length; i++) {
		var cantidad = parseFloat(cantidades[i].value) || 0;
		var precio = parseFloat(precios_venta[i].value) || 0;
		
		if (cantidad <= 0 || precio <= 0) {
			bootbox.alert("⚠️ La cantidad y el precio de venta deben ser mayores a 0");
			return;
		}
	}
	
	// Validar que el total sea mayor a 0
	var total = parseFloat($("#total_venta").val()) || 0;
	if (total <= 0) {
		bootbox.alert("⚠️ El total de la venta debe ser mayor a 0");
		return;
	}
	
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/venta.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          listar();
	    }

	});
	limpiar();
}

function mostrar(idventa)
{
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idventa").val(data.idventa);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){
	        $("#detalles").html(r);
	});	
}

//Función para anular registros
function anular(idventa)
{
	bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
		if(result)
        {
        	$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
var totalAnterior = 0; // Variable para tracking de cambios en el total
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
  {
  	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
  	if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
    }
    
    // Actualizar totales automáticamente cuando cambie el tipo de comprobante
    calcularTotales();
  }

function agregarDetalle(idarticulo,articulo,precio_venta)
  {
  	var cantidad=1;
    var descuento=0;

    if (idarticulo!="")
    {
      // Validar que el precio sea válido
      if (precio_venta <= 0 || isNaN(precio_venta)) {
        mostrarNotificacion("⚠️ Precio inválido. Se usará $1.00 como valor predeterminado.", "warning");
        precio_venta = 1;
      }
      
  		var subtotal=cantidad*precio_venta;
  		var fila='<tr class="filas" id="fila'+cont+'">'+
  		'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
  		'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
  		'<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" min="1" onchange="modificarSubototales()" onkeyup="modificarSubototales()"></td>'+
  		'<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'" min="0.01" step="0.01" onchange="modificarSubototales()" onkeyup="modificarSubototales()"></td>'+
  		'<td><input type="number" name="descuento[]" value="'+descuento+'" min="0" onchange="modificarSubototales()" onkeyup="modificarSubototales()"></td>'+
  		'<td><button type="button" onclick="eliminarDetalle('+cont+')" class="btn btn-danger"><i class="fa fa-close"></i></button>'+
  		'<input type="hidden" name="subtotal" id="subtotal'+cont+'" value="'+subtotal+'">'+
  		'</td>'+
  		'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	
    	modificarSubototales();
    }
    else
    {
    	mostrarNotificacion("❌ Error al ingresar el detalle, revisar los datos del artículo", "danger");
    }
  }

  function modificarSubototales()
  {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    var tamañoCant = cant.length;
    var totalValido = true;

    for (var i = 0; i < tamañoCant; i++) 
    {
      var inpC = cant[i];
      var inpP = prec[i];
      var inpD = desc[i];
      var inpS = sub[i];

      // Validar que los valores sean numéricos y positivos
      var cantidad = parseFloat(inpC.value) || 0;
      var precio = parseFloat(inpP.value) || 0;
      var descuento = parseFloat(inpD.value) || 0;

      if (cantidad <= 0 || precio <= 0) {
        totalValido = false;
      }

      var subtotalCalculado = (cantidad * precio) - descuento;
      document.getElementsByName("subtotal")[i].value = subtotalCalculado;
    }

    if (!totalValido && tamañoCant > 0) {
      console.warn("⚠️ Advertencia: Existen valores inválidos (cantidad o precio <= 0)");
    }

    calcularTotales();
  }
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'VES',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    var notificacion = $('<div class="alert alert-' + tipo + ' alert-dismissible fade in" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 250px;">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        mensaje +
        '</div>');
    
    $('body').append(notificacion);
    
    // Auto-cerrar después de 3 segundos
    setTimeout(function() {
        notificacion.alert('close');
    }, 3000);
}

  function calcularTotales(){
  	var sub = document.getElementsByName("subtotal");
  	var subtotal = 0.0;
  	var impuesto = parseFloat($("#impuesto").val()) || 0;
  	
  	var tamSub = sub.length;

  	for (var i = 0; i < tamSub; i++) {
  		var subtotalValue = parseFloat(document.getElementsByName("subtotal")[i].value) || 0;
  		subtotal += subtotalValue;
  	}

  	var monto_impuesto = subtotal * (impuesto / 100);
  	var total = subtotal + monto_impuesto;

  	// Mostrar desglose detallado
  	var desglose = '<div style="text-align: right; font-size: 12px;">';
  	desglose += '<div>Subtotal: ' + formatCurrency(subtotal) + '</div>';
  	if (impuesto > 0) {
  		desglose += '<div>IVA (' + impuesto + '%): ' + formatCurrency(monto_impuesto) + '</div>';
  	}
  	desglose += '<hr style="margin: 5px 0;">';
  	desglose += '<div style="font-weight: bold; font-size: 16px;">TOTAL: ' + formatCurrency(total) + '</div>';
  	desglose += '</div>';

  	$("#total").html(desglose);
    $("#total_venta").val(total.toFixed(2));
    
    // Actualizar también el total superior con formato simple
    $("#total-superior").html('<h4 style="margin: 0;">' + formatCurrency(total) + '</h4>');
    
    // Actualizar el total-footer en el pie de la tabla
    $("#total-footer").html(formatCurrency(total));
    
    // Mejoras visuales cuando hay total
    if (total > 0) {
        $("#total").addClass('alert alert-success');
        $("#total").css('padding', '10px 20px');
        $("#total-superior").addClass('alert alert-success');
        $("#total-superior").css('padding', '10px 20px');
        $("#total-footer").parent().addClass('alert alert-success');
        $("#total-footer").css('font-weight', 'bold');
        
        // Mostrar notificación de total actualizado
        if (typeof totalAnterior !== 'undefined' && total !== totalAnterior) {
            mostrarNotificacion('💰 Total actualizado: ' + formatCurrency(total), 'info');
        }
        totalAnterior = total;
    } else {
        $("#total").removeClass('alert alert-success');
        $("#total").css('padding', '10px 20px');
        $("#total-superior").removeClass('alert alert-success');
        $("#total-superior").css('padding', '10px 20px');
        $("#total-footer").parent().removeClass('alert alert-success');
        $("#total-footer").css('font-weight', 'normal');
    }
    
    evaluar();
  }

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide(); 
      cont=0;
    }
  }

  function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar()
  }

init();