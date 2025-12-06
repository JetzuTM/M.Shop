<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #d2691e;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #d2691e;
            border-color: #d2691e;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #b25014;
            border-color: #b25014;
        }

        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    session_start();
    include 'config.php';
    
    // Establecer la zona horaria a UTC
    date_default_timezone_set('UTC');
    
    // Mostrar el mensaje de éxito si está disponible
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        
        // Eliminar el mensaje de la sesión
        unset($_SESSION['success_message']);

        // Redirigir a la página de inicio después de 5 segundos
        echo '<script>
                setTimeout(function() {
                    window.location.href = "../inicio_tienda.php";
                }, 5000); // 5000 ms = 5 segundos
              </script>';
    }
    
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    
        // Verificar el token y la fecha de expiración
        $stmt = $pdo->prepare("SELECT id, expira_token FROM suscripciones WHERE token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
    
        if ($user) {
            // Verificar si el token ha expirado comparando con la hora actual en UTC
            if (strtotime($user['expira_token']) > time()) {
                ?>
                <h2>Restablecer Contraseña</h2>
                <form method="POST" action="./cambiar_contraseña.php">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cambiar Contraseña</button>
                </form>
                <?php
            } else {
                echo '<div class="alert alert-danger">El token ha expirado</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Token inválido</div>';
        }
    }
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>