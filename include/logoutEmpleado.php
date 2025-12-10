<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al login con mensaje opcional
header("Location: ../pages/login_empleado.php?error=Sesión cerrada correctamente");
exit();