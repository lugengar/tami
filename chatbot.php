<?php
// dashboard.php
session_start();
include "./codigophp/conexionbs.php";
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
    <link rel="stylesheet" href="estiloscss/animaciones.css">
    <link rel="stylesheet" href="estiloscss/styles.css">
    <link rel="stylesheet" href="estiloscss/imagenes.css">
</head>
<body>
    <div id="pagina2">
        <div id="header">
            <a href="inicio.php" class="logo imagen"></a>
            <button class="usuario imagen"></button>
        </div>
        
        <div id="contenido">
            <div class="contenido2">
                <div class="con3" id="inicio">
                <?php
                    $sql = "SELECT * FROM chatbot WHERE vendedor_fk = ".$_SESSION['id_usuario'];
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo('<iframe src="http://192.168.100.6:3000/" class="iframeqr" frameborder="0"></iframe>');
                    } else {
                        echo ('<form action="codigophp/crearbot.php"  method="post" style="color:black;">
                        <input type="text" name ="nombrebot" style="background-color:black;" required>
                        <input type="number" name ="nombrebot" style="background-color:black;" required>
                        <input type="password" name ="contraseñabot" style="background-color:black;" required>
                        <input type="submit" style="background-color:black;" >
                    </form>');
                    }
                ?>
                    
                </div>
            </div>
        </div>
        <div id="footer">
            <a href="notificaciones.php" class="campana imagen izquierda">Notificaciones</a>
            <a href="turnos.php" class="logoboton imagen centro">Turnos</a>
            <a href="inicio.php" class="flecha imagen derecha">Volver</a>
        </div>
    </div>
    <div id="sombra">
        <div class="contenidosombra">
        <button class="barra">
                <div class="equis"></div>
                    <div>Volver</div>
                    <div></div>
            </button>
            <div class="contenido2">
                <div class="con3" id="inicio">
                    <div class="scroll-y" style="height: 100%; padding-top:2vh;">
                        <div class="conscroll-y">
                                <a onclick="console.log('hola')" class="flecha imagen boton">Volver al inicio</a>                  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
      
    </style>
</body>
</html>

<script src="codigojs/sombra.js"></script>
<script src="codigojs/volveratras.js"></script>
