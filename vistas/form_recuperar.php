<form id="resetPasswordForm" method="POST" action="./php/reset_password.php">
  <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
  <label for="password">Nueva contraseña:</label>
  <input type="password" id="password" name="password" required>
  <button type="submit">Cambiar contraseña</button>
</form>