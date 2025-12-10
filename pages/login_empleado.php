<?php
session_start();
?>

<!DOCTYPE html>
<head>
  <title>Login Empleado</title>

  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="../assets/css/empleadoStyle.css">
</head>
<body>

<div class="w3-container">
  <div class="login-container w3-card-4">
    <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar" class="user-avatar">

    <?php if (isset($_GET['error'])): ?>
      <p style="color: red; text-align: center;">
        <?= htmlspecialchars($_GET['error']) ?>
      </p>
    <?php endif; ?>

    <form class="w3-container" method="POST" action="../include/login_empleado_procesar.php">
      <div class="w3-section">
        <label>
          <i class="w3-margin-right w3-large">&#128100;</i>DNI
        </label>
        <input class="w3-input w3-margin-bottom" name="dni" type="text" placeholder="DNI" required>
      </div>

      <div class="w3-section">
        <label>
          <i class="w3-margin-right w3-large">&#128274;</i>Contraseña
        </label>
        <input class="w3-input" name="contraseña" type="password" placeholder="Contraseña" required>
      </div>

      <button class="w3-button w3-block w3-black w3-margin-top" type="submit">
        Ingresar
      </button>
    </form>
  </div>
</div>

</body>
</html>
