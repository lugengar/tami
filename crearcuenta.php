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
   
    <link rel="stylesheet" href="estiloscss/styles.css">
    <link rel="stylesheet" href="estiloscss/imagenes.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/imagenes/userhd.png">
</head>
<body>
    <div id="pagina3">
        <div id="header">
            <a class="logo imagen" ></a>
            <div></div>
        
        </div>
        <div id="subheader2" class="usuariohd imagen" style="background-size:20vh;background-position:bottom; box-shadow:none;"> 
        </div>
        <div id="contenido" style="background-color:transparent; box-shadow:none;">
        <div class="contenido2">
                <div class="con3" id="inicio">
                    <h1 style="font-size: 3vh;">Crear cuenta</h1>
                    <div class="scroll-y" style="height: 100%; width:40vh; padding-top: 2vh;">
                        <form class="conscroll-y" method="post"  action="codigophp/crearcuenta.php" method="post">
                            <input type="text" class="signomas boton imagen" name="username" id="username" required placeHolder="Nombre">
                            <input type="password" class="signomas boton imagen" name="password" id="password" required  placeHolder="Contraseña">
                            <input type="mail" class="signomas boton imagen" name="correo" id="correo" required  placeHolder="Correo electronico">
                            <input type="submit" class="avion boton imagen borde" value="Crear cuenta">    
                            <a href="./index.php" class="flecha imagen boton">Inciar sesión</a>
                        </form>
                        
                    </div>
                </div>
            </div>
        
        </div>

    </div>
   
</body>
</html>

