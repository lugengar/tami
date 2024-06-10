<?php
// dashboard.php
session_start();

/*if (isset($_SESSION['id_usuario'])) {
    header("Location: ./inicio.php");
    exit;
}*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="estiloscss/login.css">
</head>
<body>
    <form class="contenedor-login" action="codigophp/iniciosesion.php" method="post">
        <img src="imagenes/logogrande.png" alt="Logo" class="logo">
        <img src="imagenes/user.png" alt="Foto de Usuario" class="foto-usuario">

        <input type="text" name="username" id="username" placeholder="Usuario" required><br>
        <input type="password" class="boton-iniciar" name="password" id="password" placeholder="Contraseña" required><br>
        <input type="submit" value="Iniciar sesión" class="boton-sesion">
        <a href="codigophp/crearcuenta.php" class="boton-crear-cuenta">Crear cuenta</a>
    </form>
</body>
</html>
