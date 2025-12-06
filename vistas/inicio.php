<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiShop</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">

</head>

<!-- Mensaje de éxito -->
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="alert alert-success text-center" role="alert" id="mensaje_exito">
        <?php
        echo $_SESSION['mensaje_exito'];
        unset($_SESSION['mensaje_exito']); // Eliminar mensaje de la sesión
        ?>
    </div>
<?php endif; ?>

<body>
    <div class="background-overlay"></div>
    <div class="login-container position-relative">
    <span class="logo"></span>
        <h1>MultiShop</h1>
        <h2>Iniciar Sesión</h2>
        <form method="post" action="../ajax/iniciar_sesion.php">
            <div class="mb-3">
                <label for="logina" class="form-label">Correo Electrónico</label>
                <input type="text" class="form-control" id="logina" name="logina" placeholder="Ingresa tu correo electrónico, nombre de usuario o teléfono" required>
            </div>
            <div class="mb-3">
                <label for="clavea" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="clavea" name="clavea" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <a href="./php/recuperar_solicitud.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <p class="text-muted">¿No tienes una cuenta? <a href="subcricion.php">Regístrate</a></p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootbox@5.5.2/dist/bootbox.min.js"></script>
</body>

</html>