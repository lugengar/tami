<?php
// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="estiloscss/styles.css">
    <link rel="stylesheet" href="estiloscss/imagenes.css">
</head>
<body>
    <div id="pagina">
        
        <div id="subheader">
            <h1>Notificaciones de <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div id="header">
            <a href="inicio.php" class="logo imagen"></a>
            <button class="usuario imagen"></button>
        </div>
        <div id="contenido">
           
            <div class="contenido2">
                <div class="con3" id="inicio">
                <h1>SERVICIOS</h1>
                    <div class="scroll-y" style="height: 100%;">
                        <div class="conscroll-y">
                        <a href="./codigophp/configurar_servicios.php" class="signomas imagen boton">Añadir servicio</a>                       
                        <a href="./codigophp/configurar_horarios.php" class="intercambio imagen boton">Cambiar horarios</a>                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <a href="inicio.php" class="flecha imagen izquierda">Volver</a>
            <a href="turnos.php" class="logoboton imagen centro">Turnos</a>
            <a href="chatbot.php" class="chatbot imagen derecha">Chatbot</a>
        </div>
    </div>
    
</body>
</html>