var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    });

    // Agregar evento para cálculo automático cuando cambie el impuesto
    $("#impuesto").on("change keyup", function() {
        modificarSubtotales();
    });

    $.post(
        "../ajax/ingreso.php?op=selectProveedor",
        function(data)
        {
            $("#idproveedor").html(data);
            $("#idproveedor").selectpicker('refresh');
        }
    );
}

//funcion limpiar
function limpiar()
{
    $("#idproveedor").val("");
    $("#proveedor").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#fecha_hora").val("");
    $("#impuesto").val("0");

    $("#total_compra").val("");
    $(".filas").remove();
    $("#total").html(0);

    //obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);
    $("#fecha_hora").val(today);

    //Marcar el primer tipo de documento
    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');

}

//funcion mostrar formulario
function mostrarform(flag)
{
    limpiar();

    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnguardar").show();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Funcion cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}

//Funcion listar
function listar()
{
    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "frtip", //Definimos los elementos del control de tabla
                buttons:[
                    
                ],
                "ajax":{
                    url: '../ajax/ingreso.php?op=listar',
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[0,"desc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}


function listarArticulos()
{
    tabla = $('#tblarticulos')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "frtip", //Definimos los elementos del control de tabla
                buttons:[
                    
                ],
                "ajax":{
                    url: '../ajax/ingreso.php?op=listarArticulos',
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[0,"desc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}

//funcion para guardar o editar
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
    
    // Validar que existan detalles
    if (detalles <= 0) {
        bootbox.alert("⚠️ Debe agregar al menos un artículo al ingreso");
        return;
    }
    
    // Validar que todos los campos de detalles tengan valores válidos
    var cantidades = document.getElementsByName("cantidad[]");
    var precios_compra = document.getElementsByName("precio_compra[]");
    
    for (var i = 0; i < cantidades.length; i++) {
        var cantidad = parseFloat(cantidades[i].value) || 0;
        var precio = parseFloat(precios_compra[i].value) || 0;
        
        if (cantidad <= 0 || precio <= 0) {
            bootbox.alert("⚠️ La cantidad y el precio de compra deben ser mayores a 0");
            return;
        }
    }
    
    // Validar que el total sea mayor a 0
    var total = parseFloat($("#total_compra").val()) || 0;
    if (total <= 0) {
        bootbox.alert("⚠️ El total del ingreso debe ser mayor a 0");
        return;
    }
    
    var formData = new FormData($("#formulario")[0]);
    
    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            //console.log("succes");
            bootbox.alert(datos);
            mostrarform(false);
            listar();

        },
        error: function(error)
        {
            console.log("error: " + error);
        } 
    });

    limpiar();
}

function mostrar(idingreso)
{
    $.post(
        "../ajax/ingreso.php?op=mostrar",
        {idingreso:idingreso},
        function(data,status)
        {

            data = JSON.parse(data);
            mostrarform(true);

            $("#idproveedor").val(data.idproveedor);
            $("#idproveedor").selectpicker('refresh');

            $("#tipo_comprobante").val(data.tipo_comprobante);
            $("#tipo_comprobante").selectpicker('refresh');
            
            $("#serie_comprobante").val(data.serie_comprobante);
            $("#num_comprobante").val(data.num_comprobante);
            $("#fecha_hora").val(data.fecha);
            $("#impuesto").val(data.impuesto);            
            $("#idingreso").val(data.ingreso); 
            
            //Ocultar y mostrar botones
            $("#btnGuardar").hide();
            $("#btnCancelar").show();
            $("#btnAgregarArt").hide();

            $.post(
                "../ajax/ingreso.php?op=listarDetalle&id="+idingreso,
                function(r)
                {
                    $("#detalles").html("");
                    $("#detalles").html(r);
                }
            );

        }
    );

    


}


function anular(idingreso)
{
    bootbox.confirm("¿Estas seguro de anular el Ingreso?",function(result){
        if(result)
        {
            $.post(
                "../ajax/ingeso.php?op=anular",
                {idingreso:idingreso},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

//Variables
var impuesto = 16;
var cont = 0;
var detalles= 0;
var totalAnterior = 0; // Variable para tracking de cambios en el total

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
{
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if(tipo_comprobante == 'Factura')
    {
        $("#impuesto").val(impuesto);
    }
    else
    {
        $("#impuesto").val('0');
    }
    
    // Actualizar totales automáticamente cuando cambie el tipo de comprobante
    modificarSubtotales();
}

function agregarDetalle(idarticulo,articulo)
{
    var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 1;

    if(idarticulo != "")
    {
        var fila = '<tr class="filas" id="fila'+cont+'">' +
                      '<td>'+
                           '<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button>'+
                       '</td>'+
                      '<td>' +
                          '<input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+
                           articulo +
                       '</td>'+
                      '<td>' +
                          '<input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" min="1" onchange="modificarSubtotales()" onkeyup="modificarSubtotales()">'+
                       '</td>'+
                      '<td>' +
                          '<input type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'" min="0.01" step="0.01" onchange="modificarSubtotales()" onkeyup="modificarSubtotales()">'+
                       '</td>'+
                      '<td>' +
                          '<input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'" min="0.01" step="0.01">'+
                       '</td>'+
                      '<td>' +
                          '<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">'+
                            '<i class="fa fa-close"></i>'+
                          '</button>'+
                          '<input type="hidden" name="subtotal" id="subtotal'+cont+'" value="'+(cantidad * precio_compra)+'">'+
                       '</td>'+
                   '</tr>';

        cont++;
        detalles++;
        $("#detalles").append(fila);
        modificarSubtotales(); 
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del articulo");
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'VES',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

function modificarSubtotales()
{
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var sub = document.getElementsByName("subtotal");

    var tamañoCant = cant.length;
    var totalValido = true;

    for (var i = 0; i < tamañoCant; i++) 
    {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];

        // Validar que los valores sean numéricos y positivos
        var cantidad = parseFloat(inpC.value) || 0;
        var precio = parseFloat(inpP.value) || 0;

        if (cantidad <= 0 || precio <= 0) {
            totalValido = false;
        }

        var subtotalCalculado = cantidad * precio;
        document.getElementsByName("subtotal")[i].value = subtotalCalculado;
    }

    if (!totalValido && tamañoCant > 0) {
        console.warn("⚠️ Advertencia: Existen valores inválidos (cantidad o precio <= 0)");
    }

    calcularTotales();
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

function calcularTotales()
{
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
    $("#total-footer").html(formatCurrency(total));
    $("#total_compra").val(total.toFixed(2));

    // Mostrar notificación visual del total
    if (total > 0) {
        $("#total").addClass('alert alert-success');
        $("#total").css('padding', '10px 20px');
        $("#total-footer").parent().addClass('alert alert-success');
        
        // Mostrar notificación de total actualizado
        if (typeof totalAnterior !== 'undefined' && total !== totalAnterior) {
            mostrarNotificacion('💰 Total actualizado: ' + formatCurrency(total), 'info');
        }
        totalAnterior = total;
    } else {
        $("#total").removeClass('alert alert-success');
        $("#total").css('padding', '10px 20px');
        $("#total-footer").parent().removeClass('alert alert-success');
    }

    evaluar();
}

function evaluar()
{
    if(detalles > 0)
    {
        $("#btnGuardar").show();
    }
    else
    {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice)
{
    $("#fila" + indice).remove();

    detalles -= 1;

    calcularTotales();

    
}

init();