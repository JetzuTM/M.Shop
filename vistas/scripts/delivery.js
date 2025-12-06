var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    })

    $("#imagenmuestra").hide();
}

//funcion limpiar
function limpiar() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#precio_venta").val("");  // Limpiamos el campo de precio de venta
    $("#precio_compra").val("");  // Limpiamos el campo de precio de compra
    $("#imagenmuestra").attr("src","");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#iddelivery").val("");
}

//funcion mostrar formulario
function mostrarform(flag)
{
    limpiar();

    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
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
                    'pdf'
                ],
                "ajax":{
                    url: '../ajax/delivery.php?op=listar',
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
function guardaryeditar(e) {
    e.preventDefault(); // No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]); // Incluye todos los campos automáticamente
    
    $.ajax({
        url: "../ajax/delivery.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        },
        error: function(error) {
            console.log("error: " + error);
        }
    });

    limpiar();
}

function mostrar(iddelivery) {
    $.post("../ajax/delivery.php?op=mostrar", {iddelivery:iddelivery}, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#precio_venta").val(data.precio_venta);  // Mostramos el precio de venta
        $("#precio_compra").val(data.precio_compra);  // Mostramos el precio de compra
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/fondos/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#iddelivery").val(data.iddelivery);

        generarbarcode();
    });
}

//funcion para descativar categorias
function desactivar(iddelivery)
{
    bootbox.confirm("¿Estas seguro de desactivar este empleado?",function(result){
        if(result)
        {
            $.post(
                "../ajax/delivery.php?op=desactivar",
                {iddelivery:iddelivery},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

function activar(iddelivery)
{
    bootbox.confirm("¿Estas seguro de desactivar a este Empleado?",function(result){
        if(result)
        {
            $.post(
                "../ajax/delivery.php?op=activar",
                {iddelivery:iddelivery},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

function generarbarcode()
{
    var codigo = $("#codigo").val();
    JsBarcode("#barcode",codigo);
    $("#print").show();
}

function imprimir()
{
    $("#print").printArea();
}

init();