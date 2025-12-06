<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url("../../imagenes/fondoin.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .background-overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }

        

        .login-container h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 2rem;
        }

        .btn-primary {
            background-color: black;
            border-color: black;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: purple;
            border-color: purple;
        }

        .form-control {
            padding: 0.75rem;
            border-radius: 10px;
        }

        .alert {
            margin-top: 1rem;
        }

        .logo {
            display: inline-block;
            width: 90px; /* Ajusta el ancho según tus necesidades */
            height: 90px; /* Ajusta la altura según tus necesidades */
            background-image: url("../../public/img/Multi.png");
            background-size: contain;
            background-repeat: no-repeat;
            padding: 0.75rem;
            display: block;
            margin: 0 auto;
            margin-bottom: 1rem;
          }
    </style>
</head>
<body>
<div class="background-overlay"></div>
<div class="login-container position-relative">
<span class="logo"></span>
    <h2>Recuperar Contraseña</h2>
    <form id="recuperar-form">
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <input type="hidden" name="token" id="token" value="<?php echo $token; ?>"> <!-- Token -->
        <button type="submit" class="btn btn-primary btn-block">Recuperar Contraseña</button>
    </form>
    <!-- Mensaje de éxito -->
    <div id="mensaje-exito" class="alert alert-success d-none" role="alert">
        ¡Correo de recuperación enviado con éxito!
    </div>
    <!-- Mensaje de error -->
    <div id="mensaje-error" class="alert alert-danger d-none" role="alert">
        Ocurrió un error al enviar el correo. Inténtalo de nuevo.
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#recuperar-form').on('submit', function(e) {
            e.preventDefault(); // Prevenir que se recargue la página

            $.ajax({
                type: 'POST',
                url: './enviar_correo.php', // URL de tu archivo PHP que procesa el envío
                data: $(this).serialize(),
                success: function(response) {
                    // Mostrar el mensaje de éxito
                    $('#mensaje-exito').removeClass('d-none');
                    $('#mensaje-error').addClass('d-none');

                    // Después de 3 segundos, redirigir a la página anterior
                    setTimeout(function() {
                        window.history.back(); // O puedes usar `window.location.href = 'URL'` para redirigir a una URL específica
                    }, 3000); // 3000 milisegundos = 3 segundos
                },
                error: function() {
                    // Mostrar el mensaje de error si falla el envío
                    $('#mensaje-error').removeClass('d-none');
                    $('#mensaje-exito').addClass('d-none');
                }
            });
        });
    });
</script>
</body>
</html>