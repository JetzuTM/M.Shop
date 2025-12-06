var tabla;

// Función que se ejecuta al inicio
function init()
{
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    $("#imagenmuestra").hide();

    // Mostramos los permisos
    $.post(
        "../ajax/usuario.php?op=permisos&id=",
        function(data)
        {
            $("#permisos").html(data);
            seleccionarYDesactivarPermisoEscritorio();
        }
    );
}

// Función para asegurar que "Escritorio" esté siempre seleccionado y desactivado
function seleccionarYDesactivarPermisoEscritorio()
{
    var permisoEscritorioID = 1; // Asegúrate de que este ID corresponda a "Escritorio" en tu backend
    $('input[name="permiso[]"][value="' + permisoEscritorioID + '"]').prop('checked', true).prop('disabled', true);
}

// Función limpiar
function limpiar()
{
    $("#nombre").val("");
    $("#tipo_documento").val("CEDULA").change(); // Asumiendo que por defecto es 'CEDULA'
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");

    $("#imagenmuestra").attr("src", "").hide();
    $("#imagenactual").val("");

    $("#idusuario").val("");
}

// Función mostrar formulario
function mostrarform(flag)
{
    limpiar();

    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

// Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}

// Función listar
function listar()
{
    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing": true, // Activamos el procesamiento de datatables
                "aServerSide": true, // Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", // Definimos los elementos del control de tabla
                buttons: [
                    'pdf'
                ],
                "ajax": {
                    url: '../ajax/usuario.php?op=listar',
                    type: "get",
                    dataType: "json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, // Paginación
                "order": [[0, "desc"]] // Ordenar (Columna, orden)
            })
        .DataTable();
}

// Función para guardar o editar
function guardaryeditar(e)
{
    e.preventDefault(); // No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);

    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        },
        error: function(error)
        {
            console.log("error: " + error);
            $("#btnGuardar").prop("disabled", false);
        } 
    });

    limpiar();
}

// Función para mostrar los datos de un usuario existente
function mostrar(idusuario)
{
    $.post(
        "../ajax/usuario.php?op=mostrar",
        {idusuario: idusuario},
        function(data, status)
        {
            data = JSON.parse(data);
            mostrarform(true);

            $("#nombre").val(data.nombre);
            $("#tipo_documento").val(data.tipo_documento).change();
            $("#num_documento").val(data.num_documento);
            $("#direccion").val(data.direccion);
            $("#telefono").val(data.telefono);
            $("#email").val(data.email);
            $("#cargo").val(data.cargo);
            $("#login").val(data.login);
            $("#clave").val(""); // Por seguridad, no mostramos la contraseña

            if (data.imagen != "") {
                $("#imagenmuestra").show(); 
                $("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen); // Agregamos el atributo src para mostrar la imagen
                $("#imagenactual").val(data.imagen);
            } else {
                $("#imagenmuestra").hide();
                $("#imagenactual").val("");
            }

            $("#idusuario").val(data.idusuario);
        }
    );

    $.post(
        "../ajax/usuario.php?op=permisos&id=" + idusuario,
        function(data)
        {
            $("#permisos").html(data);
            seleccionarYDesactivarPermisoEscritorio();
        }
    );
}

// Función para desactivar usuarios
function desactivar(idusuario)
{
    bootbox.confirm("¿Estás seguro de desactivar el Usuario?", function(result){
        if(result)
        {
            $.post(
                "../ajax/usuario.php?op=desactivar",
                {idusuario: idusuario},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

// Función para activar usuarios
function activar(idusuario)
{
    bootbox.confirm("¿Estás seguro de activar el Usuario?", function(result){
        if(result)
        {
            $.post(
                "../ajax/usuario.php?op=activar",
                {idusuario: idusuario},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

init();