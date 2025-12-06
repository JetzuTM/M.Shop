<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <!-- Incluye intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        .iti {
            width: 100%;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
<body>
    <section class="vh-100 background-image-overlay">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg" style="border-radius: 15px;">
                        <div class="card-body p-5">
                        <div class="login-container position-relative">
                        <div class="d-flex justify-content-center mb-4">
                        <img src="../public/img/Multi.png" style="max-width: 90px;"></div>                        
                            <h1>MultiShop</h1>
                            <h2>Registro</h2>
                            <form action="../ajax/registro.php" method="post" onsubmit="return validateForm()">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <label for="name">Nombre Completo</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <label for="email">Correo Electrónico</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <!-- Campo de teléfono con validaciones completas -->
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                        <div class="invalid-feedback" id="phone-error"></div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <ul class="text-muted small ps-3 mb-0">
                                            <li>Debe incluir código de país</li>
                                            <li>Solo se permiten números, + y -</li>
                                            <li>Mínimo 8 dígitos (sin contar código)</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="cedula" name="cedula" required pattern="[0-9]{7,8}">
                                    <label for="cedula">Número de Cédula</label>
                                    <div class="form-text">La cédula debe tener entre 7 y 8 dígitos.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                    <label for="password">Contraseña</label>
                                    <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-lg w-100">Suscribirse</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contenedor para alertas flotantes -->
    <div id="alertContainer"></div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <!-- Incluye intl-tel-input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        function validateForm() {
            // Validar todos los campos
            const nombreValido = validarNombre();
            const emailValido = validarEmail();
            const telefonoValido = validatePhone();
            const cedulaValida = validarCedula();
            const passwordValido = validarPassword();
            
            // Mostrar alertas para campos inválidos
            if (!nombreValido || !emailValido || !telefonoValido || !cedulaValida || !passwordValido) {
                mostrarAlerta('Por favor, corrija los errores en el formulario', 'danger');
                return false;
            }
            
            // Si todo es válido, guardar el teléfono en formato E.164
            document.getElementById('phone').value = phoneInput.getNumber();
            return true;
        }
        
        // Función para mostrar alertas flotantes
        function mostrarAlerta(mensaje, tipo) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const alertHTML = `
                <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show alert-float" role="alert">
                    ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            alertContainer.insertAdjacentHTML('beforeend', alertHTML);
            
            // Auto-cerrar después de 5 segundos
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
                    bsAlert.close();
                }
            }, 5000);
        }
        
        // Función para validar el nombre
        function validarNombre() {
            const nombreInput = document.getElementById('name');
            const errorMsg = document.getElementById('name-error');
            
            // Limpiar mensajes de error previos
            nombreInput.classList.remove('is-invalid', 'is-valid');
            errorMsg.textContent = '';
            
            // Verificar si está vacío
            if (!nombreInput.value.trim()) {
                nombreInput.classList.add('is-invalid');
                errorMsg.textContent = 'El nombre es obligatorio';
                return false;
            }
            
            // Verificar longitud mínima
            if (nombreInput.value.trim().length < 3) {
                nombreInput.classList.add('is-invalid');
                errorMsg.textContent = 'El nombre debe tener al menos 3 caracteres';
                return false;
            }
            
            // Verificar que solo contenga letras y espacios
            if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombreInput.value.trim())) {
                nombreInput.classList.add('is-invalid');
                errorMsg.textContent = 'El nombre solo debe contener letras y espacios';
                return false;
            }
            
            // Si pasa todas las validaciones
            nombreInput.classList.add('is-valid');
            return true;
        }

        // Función para validar el email
        function validarEmail() {
            const emailInput = document.getElementById('email');
            const errorMsg = document.getElementById('email-error');
            
            // Limpiar mensajes de error previos
            emailInput.classList.remove('is-invalid', 'is-valid');
            errorMsg.textContent = '';
            
            // Verificar si está vacío
            if (!emailInput.value.trim()) {
                emailInput.classList.add('is-invalid');
                errorMsg.textContent = 'El correo electrónico es obligatorio';
                return false;
            }
            
            // Verificar formato de email
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                errorMsg.textContent = 'Ingrese un correo electrónico válido';
                return false;
            }
            
            // Si pasa todas las validaciones
            emailInput.classList.add('is-valid');
            return true;
        }

        // Función para validar la cédula
        function validarCedula() {
            const cedulaInput = document.getElementById('cedula');
            const errorMsg = document.getElementById('cedula-error');
            
            // Limpiar mensajes de error previos
            cedulaInput.classList.remove('is-invalid', 'is-valid');
            errorMsg.textContent = '';
            
            // Verificar si está vacío
            if (!cedulaInput.value.trim()) {
                cedulaInput.classList.add('is-invalid');
                errorMsg.textContent = 'La cédula es obligatoria';
                return false;
            }
            
            // Verificar que solo contenga números
            if (!/^\d+$/.test(cedulaInput.value.trim())) {
                cedulaInput.classList.add('is-invalid');
                errorMsg.textContent = 'La cédula solo debe contener números';
                return false;
            }
            
            // Verificar longitud (entre 7 y 8 dígitos)
            const longitud = cedulaInput.value.trim().length;
            if (longitud < 7 || longitud > 8) {
                cedulaInput.classList.add('is-invalid');
                errorMsg.textContent = 'La cédula debe tener entre 7 y 8 dígitos';
                return false;
            }
            
            // Si pasa todas las validaciones
            cedulaInput.classList.add('is-valid');
            return true;
        }

        // Función para validar la contraseña
        function validarPassword() {
            const passwordInput = document.getElementById('password');
            const errorMsg = document.getElementById('password-error');
            
            // Limpiar mensajes de error previos
            passwordInput.classList.remove('is-invalid', 'is-valid');
            errorMsg.textContent = '';
            
            const password = passwordInput.value;
            
            // Verificar si está vacío
            if (!password) {
                passwordInput.classList.add('is-invalid');
                errorMsg.textContent = 'La contraseña es obligatoria';
                return false;
            }
            
            // Verificar longitud mínima
            if (password.length < 8) {
                passwordInput.classList.add('is-invalid');
                errorMsg.textContent = 'La contraseña debe tener al menos 8 caracteres';
                return false;
            }
            
            // Verificar que contenga al menos una letra mayúscula
            if (!/[A-Z]/.test(password)) {
                passwordInput.classList.add('is-invalid');
                errorMsg.textContent = 'La contraseña debe contener al menos una letra mayúscula';
                return false;
            }
            
            // Verificar que contenga al menos un número
            if (!/[0-9]/.test(password)) {
                passwordInput.classList.add('is-invalid');
                errorMsg.textContent = 'La contraseña debe contener al menos un número';
                return false;
            }
            
            // Si pasa todas las validaciones
            passwordInput.classList.add('is-valid');
            return true;
        }
        
        // Inicializar intl-tel-input
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "ve", // Establecer país inicial a Venezuela
            preferredCountries: ["ve", "co", "es", "us"],
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        // Validación en tiempo real del teléfono
        phoneInputField.addEventListener('input', function() {
            validatePhone(true);
        });
        
        phoneInputField.addEventListener('blur', function() {
            validatePhone();
        });

        // Función completa de validación de teléfono
        function validatePhone(isTyping = false) {
            const phoneError = document.getElementById('phone-error');
            
            // Limpiar mensajes de error previos
            phoneError.textContent = '';
            phoneInputField.classList.remove('is-invalid');
            
            // Si está escribiendo, no mostrar errores todavía
            if (isTyping && phoneInputField.value.length < 8) {
                return true;
            }
            
            // Verificar si el campo está vacío
            if (!phoneInputField.value.trim()) {
                phoneError.textContent = 'El número de teléfono es obligatorio';
                phoneInputField.classList.add('is-invalid');
                return false;
            }
            
            // Verificar si el número es válido según la librería
            if (!phoneInput.isValidNumber()) {
                const errorCode = phoneInput.getValidationError();
                let errorMsg = 'Número de teléfono inválido';
                
                // Mensajes específicos según el tipo de error
                switch(errorCode) {
                    case intlTelInputUtils.validationError.INVALID_COUNTRY_CODE:
                        errorMsg = 'Código de país inválido';
                        break;
                    case intlTelInputUtils.validationError.TOO_SHORT:
                        errorMsg = 'El número es demasiado corto';
                        break;
                    case intlTelInputUtils.validationError.TOO_LONG:
                        errorMsg = 'El número es demasiado largo';
                        break;
                    case intlTelInputUtils.validationError.NOT_A_NUMBER:
                        errorMsg = 'Solo se permiten números, + y -';
                        break;
                }
                
                phoneError.textContent = errorMsg;
                phoneInputField.classList.add('is-invalid');
                return false;
            }
            
            // Verificar formato específico según el país
            const countryCode = phoneInput.getSelectedCountryData().iso2.toUpperCase();
            const phoneNumber = phoneInput.getNumber();
            
            // Validaciones específicas por país
            switch(countryCode) {
                case 'VE': // Venezuela
                    if (!/^\+58[0-9]{10}$/.test(phoneNumber)) {
                        phoneError.textContent = 'Formato inválido para Venezuela (+58 seguido de 10 dígitos)';
                        phoneInputField.classList.add('is-invalid');
                        return false;
                    }
                    break;
                case 'CO': // Colombia
                    if (!/^\+57[0-9]{10}$/.test(phoneNumber)) {
                        phoneError.textContent = 'Formato inválido para Colombia (+57 seguido de 10 dígitos)';
                        phoneInputField.classList.add('is-invalid');
                        return false;
                    }
                    break;
                // Puedes agregar más países según sea necesario
            }
            
            // Si pasa todas las validaciones, guardar el número en formato E.164
            document.getElementById('phone').value = phoneNumber;
            return true;
        }
    </script>
</body>
</html>