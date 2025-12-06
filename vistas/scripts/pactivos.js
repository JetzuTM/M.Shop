var tabla;

// Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    // Cargamos los items al select categoria
    $.post(
        "../ajax/pactivos.php?op=selectCategoria",
        function(data) {        
            //console.log(data);
            $("#idcategoria").html(data);
            $("#idcategoria").selectpicker('refresh');
        }
    );

    $("#imagenmuestra").hide();
}

// Función limpiar
function limpiar() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#idarticulo").val("");
}

// Función mostrar formulario
function mostrarform(flag) {
    limpiar();

    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

// Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

// Función listar
function listar() {
    tabla = $('#tblistado')
        .dataTable({
            "aProcessing": true, // Activamos el procesamiento del datatables
            "aServerSide": true, // Paginacion y filtrado realizados por el servidor
            dom: "frtip", // Definimos los elementos del control de tabla
            buttons: [                   
                'pdf'
            ],
            "ajax": {
                url: '../ajax/pactivos.php?op=listar',
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, // Paginacion
            "order": [[0, "desc"]] // Ordenar (Columna, orden)
        })
        .DataTable();
}

// Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); // No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]); // Incluye todos los campos automáticamente
    
    $.ajax({
        url: "../ajax/pactivos.php?op=guardaryeditar",
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

function mostrar(idarticulo) {
    $.post("../ajax/pactivos.php?op=mostrar", { idarticulo: idarticulo }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/fondos/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idarticulo").val(data.idarticulo);

        generarbarcode();
    });
}

// Función para desactivar categorías
function desactivar(idarticulo) {
    bootbox.confirm("¿Estás seguro de desactivar el Artículo?", function(result) {
        if (result) {
            $.post(
                "../ajax/pactivos.php?op=desactivar",
                { idarticulo: idarticulo },
                function(e) {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

function activar(idarticulo) {
    bootbox.confirm("¿Estás seguro de activar el Artículo?", function(result) {
        if (result) {
            $.post(
                "../ajax/pactivos.php?op=activar",
                { idarticulo: idarticulo },
                function(e) {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

function generarbarcode() {
    var codigo = $("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}

function imprimir() {
    $("#print").printArea();
}

init();