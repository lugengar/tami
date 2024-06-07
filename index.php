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
        <img src="logo.jpg" alt="Logo" class="logo">
        <img src="fotousuario.jpg" alt="Foto de Usuario" class="foto-usuario">

        <input type="text" name="username" id="username" required><br>
     
        <input type="password" class="boton-iniciar" name="password" id="password" required><br>
        <input type="submit" value="Iniciar sesión">
        <button class="boton-olvidado">Me olvidé mi contraseña</button>
    </form>
</body>
</html>
