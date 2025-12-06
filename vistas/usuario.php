<?php
// Activación de almacenamiento en buffer
ob_start();
// Iniciamos las variables de sesión
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit();
} else {
    require 'header.php';

    if ($_SESSION['acceso'] == 1) {
?>

<!-- Contenido -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Usuarios <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                    </div>
                    
                    <!-- Listado de registros -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Numero Doc.</th>
                                <th>Telefono</th>
                                <th>Email</th>
                                <th>Login</th>
                                <th>Foto</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    
                    <!-- Formulario de registro -->
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="idusuario" id="idusuario">
                            <input type="hidden" name="_token" id="_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
                            
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <label>Nombre y Apellido:</label>
    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="20" 
           placeholder="Nombre y Apellido" required
           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,20}" 
           title="Solo letras, espacios y acentos (3-20 caracteres)">
    <div class="invalid-feedback">
        Solo letras, espacios y acentos permitidos (3-20 caracteres)
    </div>
</div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Tipo Documento:</label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                                        <option value="CEDULA">CEDULA</option>
                                        <option value="DNI">RIF</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Numero Doc.:</label>
    <input type="text" class="form-control" name="num_documento" id="num_documento" 
           maxlength="10" placeholder="Numero Doc." required
           pattern="[0-9]+" 
           title="Ingrese solo números (8 dígitos para Cédula, 10 para RIF)">
    <div class="invalid-feedback" id="doc-error"></div>
</div>
</div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Dirección:</label>
                                    <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" placeholder="Dirección">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Teléfono:</label>
    <input type="text" class="form-control" name="telefono" id="telefono" 
           maxlength="12" placeholder="Ej: 0412-1234567 o 0212-1234567" required
           title="Formato válido: Móvil: 0412-1234567 / Fijo: 0212-1234567">
    <div class="invalid-feedback" id="telefono-error"></div>
    <small class="form-text text-muted"></small>
</div>
</div>
                            <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Email:</label>
    <input type="email" class="form-control" name="email" id="email" 
           maxlength="20" placeholder="usuario@dominio.com"
           pattern="^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com|outlook\.com|yahoo\.com|icloud\.com|protonmail\.com)$"
           title="Solo se permiten correos de dominios válidos (Gmail, Hotmail, etc.)">
    <div class="invalid-feedback">El email debe ser de un dominio válido</div>
    <small class="form-text text-muted"></small>
</div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Cargo:</label>
                                    <input type="text" class="form-control" name="cargo" id="cargo" maxlength="8" placeholder="Cargo" required>
                                </div>
                            </div>

                            <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Login(*):</label>
    <input type="text" class="form-control" name="login" id="login" 
           maxlength="8" placeholder="Nombre de usuario" required
           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
           title="Máximo 8 digitos">
    <div class="invalid-feedback">minimo 3 hasta 8 digitos</div>
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Clave(*):</label>
    <input type="password" class="form-control" name="clave" id="clave" 
           minlength="5" maxlength="20" placeholder="Contraseña" required
           title="La contraseña debe tener entre 5 y 20 caracteres">
    <div class="invalid-feedback">La contraseña debe tener entre 5 y 20 caracteres</div>
    <small class="form-text text-muted"></small>
</div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Permisos <span class="text-danger">*</span>:</label>
                                    <ul style="list-style:none;" id="permisos">
                                        <li>
                                            <input type="checkbox" name="permiso[]" value="1" id="permiso_escritorio" checked disabled>
                                            <label for="permiso_escritorio">Escritorio</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" name="permiso[]" value="2" id="permiso_otro1">
                                            <label for="permiso_otro1">Otro Permiso 1</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" name="permiso[]" value="3" id="permiso_otro2">
                                            <label for="permiso_otro2">Otro Permiso 2</label>
                                        </li>
                                    </ul>
                                    <div id="errorPermisos" class="text-danger" style="display: none;">Debes seleccionar al menos un permiso.</div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Imagen:</label>
                                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*">
                                    <input type="hidden" class="form-control" name="imagenactual" id="imagenactual">
                                    <img src="" width="150px" height="120px" id="imagenmuestra" class="mt-2">
                                    <div class="invalid-feedback">Solo imágenes JPG, PNG o GIF (max 2MB)</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                    <button class="btn btn-danger" onclick="cancelarform()" type="button">
                                        <i class="fa fa-arrow-circle-left"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
    } else {
        require 'noacceso.php';
    }

    require 'footer.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
<script src="./scripts/usuario.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    // Inicializar el input de teléfono
    const phoneInput = window.intlTelInput(document.querySelector("#telefono"), {
        initialCountry: "ve",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        separateDialCode: true
    });

    // Validación en tiempo real del documento
document.getElementById('tipo_documento').addEventListener('change', function() {
    validarDocumento();
    // Actualizar maxlength según el tipo de documento
    if(this.value === 'CEDULA') {
        document.getElementById('num_documento').maxLength = 8;
    } else {
        document.getElementById('num_documento').maxLength = 10;
    }
});

document.getElementById('num_documento').addEventListener('input', function() {
    // Solo permitir números
    this.value = this.value.replace(/[^0-9]/g, '');
    // Limitar la longitud según el tipo de documento
    const tipo = document.getElementById('tipo_documento').value;
    if(tipo === 'CEDULA' && this.value.length > 8) {
        this.value = this.value.substring(0, 8);
    } else if(tipo === 'DNI' && this.value.length > 10) {
        this.value = this.value.substring(0, 10);
    }
    validarDocumento();
});

function validarDocumento() {
    const tipo = document.getElementById('tipo_documento').value;
    const numero = document.getElementById('num_documento').value;
    const errorElement = document.getElementById('doc-error');
    const inputElement = document.getElementById('num_documento');
    
    let valido = false;
    let mensaje = '';
    
    if (tipo === 'CEDULA') {
        valido = /^[0-9]{8}$/.test(numero);
        mensaje = valido ? '' : 'La cédula debe tener exactamente 8 dígitos';
    } else if (tipo === 'DNI') {
        valido = /^[0-9]{10}$/.test(numero);
        mensaje = valido ? '' : 'El RIF debe tener exactamente 10 dígitos';
    }
    
    inputElement.classList.toggle('is-invalid', !valido);
    errorElement.textContent = mensaje;
    
    // Deshabilitar el botón de guardar si no es válido
    document.getElementById('btnGuardar').disabled = !valido;
    
    return valido;
}

// Llamar a validarDocumento al cargar la página para establecer el estado inicial
validarDocumento();

    // Validación de nombre en tiempo real
    document.getElementById('nombre').addEventListener('input', function() {
        const nombre = this.value;
        const valido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,50}$/.test(nombre);
        this.classList.toggle('is-invalid', !valido);
    });

// Validación completa para teléfonos venezolanos
document.getElementById('telefono').addEventListener('input', function(e) {
    // Obtener valor sin formato
    let value = this.value.replace(/[^0-9]/g, '');
    let formatted = '';
    let valido = false;
    let mensaje = '';
    
    // Móvil (04xx-xxxxxxx)
    if (/^04/.test(value)) {
        if (value.length > 4) {
            formatted = value.substring(0, 4) + '-' + value.substring(4, 11);
        } else {
            formatted = value;
        }
        
        valido = /^04[1246][0-9]-[0-9]{7}$/.test(formatted) && value.length === 11;
        mensaje = valido ? '' : 'Móvil inválido. Formato: 0412-1234567 (11 dígitos)';
    } 
    // Fijo (02xx-xxxxxxx)
    else if (/^02/.test(value)) {
        if (value.length > 4) {
            formatted = value.substring(0, 4) + '-' + value.substring(4, 11);
        } else {
            formatted = value;
        }
        
        valido = /^02[1-9][0-9]-[0-9]{7}$/.test(formatted) && value.length === 11;
        mensaje = valido ? '' : 'Fijo inválido. Formato: 0212-1234567 (11 dígitos)';
    } 
    // Números especiales (0800, 0900, etc.)
    else if (/^0[89]00/.test(value)) {
        if (value.length > 4) {
            formatted = value.substring(0, 4) + '-' + value.substring(4, 7);
        } else {
            formatted = value;
        }
        
        valido = /^0[89]00-[0-9]{3,7}$/.test(formatted) && value.length >= 7 && value.length <= 11;
        mensaje = valido ? '' : 'Número especial inválido. Formato: 0800-123456';
    } 
    // Otros casos
    else {
        formatted = value;
        mensaje = 'Formato inválido. Debe comenzar con 04 (móvil), 02 (fijo)';
    }
    
    // Aplicar formato visual
    this.value = formatted;
    
    // Validación
    const errorElement = document.getElementById('telefono-error');
    this.classList.toggle('is-invalid', !valido);
    errorElement.textContent = mensaje;
    document.getElementById('btnGuardar').disabled = !valido;
});

// Lista completa de códigos válidos en Venezuela
const codigosValidos = {
    moviles: ['0412', '0414', '0416', '0424', '0426', '0415', '0425', '0432', '0434', '0442', '0452', '0462', '0472', '0482', '0492'],
    fijos: ['0212', '0234', '0235', '0237', '0238', '0241', '0242', '0243', '0244', '0245', '0246', '0247', '0248', '0249', '0251', '0252', '0253', '0254', '0255', '0256', '0257', '0258', '0259', '0261', '0262', '0263', '0264', '0265', '0266', '0267', '0268', '0269', '0271', '0272', '0273', '0274', '0275', '0276', '0277', '0278', '0279', '0281', '0282', '0283', '0284', '0285', '0286', '0287', '0288', '0289', '0291', '0292', '0293', '0294', '0295', '0296', '0297', '0298', '0299'],
    especiales: ['0800', '0900']
};

// Validación al enviar el formulario
document.getElementById('formulario').addEventListener('submit', function(e) {
    const telefono = document.getElementById('telefono').value.replace(/-/g, '');
    const codigo = telefono.substring(0, 4);
    let valido = false;
    
    // Verificar según tipo de número
    if (codigosValidos.moviles.includes(codigo)) {
        valido = telefono.length === 11;
    } else if (codigosValidos.fijos.includes(codigo)) {
        valido = telefono.length === 11;
    } else if (codigosValidos.especiales.includes(codigo)) {
        valido = telefono.length >= 7 && telefono.length <= 11;
    }
    
    if (!valido) {
        e.preventDefault();
        document.getElementById('telefono').classList.add('is-invalid');
        document.getElementById('telefono-error').textContent = 'Número telefónico inválido para Venezuela';
        document.getElementById('telefono').focus();
    }
});

    // Lista de dominios permitidos
const dominiosPermitidos = [
    'gmail.com',
    'hotmail.com',
    'outlook.com',
    'yahoo.com',
    'icloud.com',
    'protonmail.com'
];

// Validación en tiempo real del email
document.getElementById('email').addEventListener('input', function() {
    const email = this.value;
    const maxLength = 20;
    
    // Validar longitud máxima
    if (email.length > maxLength) {
        this.value = email.substring(0, maxLength);
        return;
    }
    
    // Validar formato y dominio
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let valido = false;
    
    if (email && regex.test(email)) {
        const dominio = email.split('@')[1].toLowerCase();
        valido = dominiosPermitidos.includes(dominio);
    }
    
    this.classList.toggle('is-invalid', email && !valido);
    document.getElementById('btnGuardar').disabled = email && !valido;
});

// Validación al enviar el formulario
document.getElementById('formulario').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    
    if (email) {  // Solo validar si se ingresó un email (no requerido)
        const dominio = email.split('@')[1]?.toLowerCase();
        
        if (!dominio || !dominiosPermitidos.includes(dominio)) {
            e.preventDefault();
            document.getElementById('email').classList.add('is-invalid');
            document.getElementById('email').focus();
        }
    }
});

    // Validación en tiempo real del login
document.getElementById('login').addEventListener('input', function() {
    // Eliminar números y caracteres especiales
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    
    // Validar que contenga al menos 3 letras
    const valido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,20}$/.test(this.value);
    this.classList.toggle('is-invalid', !valido);
    document.getElementById('btnGuardar').disabled = !valido;
});

// Validación al enviar el formulario
document.getElementById('formulario').addEventListener('submit', function(e) {
    const login = document.getElementById('login').value;
    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,20}$/.test(login)) {
        e.preventDefault();
        document.getElementById('login').classList.add('is-invalid');
        document.getElementById('login').focus();
    }
});

    // Validación en tiempo real de la contraseña
document.getElementById('clave').addEventListener('input', function() {
    // Limitar a 20 caracteres
    if(this.value.length > 20) {
        this.value = this.value.substring(0, 20);
    }
    
    // Validar longitud
    const valido = this.value.length >= 5;
    this.classList.toggle('is-invalid', !valido);
    document.getElementById('btnGuardar').disabled = !valido;
});

// Validación al enviar el formulario
document.getElementById('formulario').addEventListener('submit', function(e) {
    const clave = document.getElementById('clave').value;
    if(clave.length < 5 || clave.length > 20) {
        e.preventDefault();
        document.getElementById('clave').classList.add('is-invalid');
        document.getElementById('clave').focus();
    }
});

    // Validación de imagen
    document.getElementById('imagen').addEventListener('change', function() {
        const archivo = this.files[0];
        if (!archivo) return;
        
        const extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        const extension = archivo.name.split('.').pop().toLowerCase();
        const tamanoMaximo = 2 * 1024 * 1024; // 2MB
        
        const valido = extensionesPermitidas.includes(extension) && archivo.size <= tamanoMaximo;
        this.classList.toggle('is-invalid', !valido);
        
        if (valido) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagenmuestra').src = e.target.result;
            }
            reader.readAsDataURL(archivo);
        }
    });

    // Validación de permisos
    document.querySelectorAll('input[name="permiso[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const permisos = document.querySelectorAll('input[name="permiso[]"]:checked');
            document.getElementById('errorPermisos').style.display = permisos.length === 0 ? 'block' : 'none';
        });
    });
</script>

<?php
}
ob_end_flush();
?>