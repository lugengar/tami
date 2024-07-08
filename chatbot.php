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
    <link rel="icon" type="image/png" sizes="32x32" href="/imagenes/userhd.png">
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
                    $sql = "SELECT * FROM chatbot WHERE chatbot.usuario_fk = '".$_SESSION['id_usuario']."'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo('<iframe src="http://192.168.100.6:3000/" class="iframeqr" frameborder="0"></iframe>');
                    } else {
              
                        echo (' <h1>CREAR CHATBOT</h1><form class="conscroll-y" action="codigophp/crearbot.php" method="post" style="color:white;width:40vh;">
                        <input type="text" class="signomas imagen boton" name ="nombrebot" style="color:white;" required placeHolder="Nombre del bot">
                        <input type="number" class="signomas imagen boton"name ="telefonobot" style="color:white;" required placeHolder="Telefono del bot">
                        <input type="password" class="signomas imagen boton" name ="contraseñabot" style="color:white;" required placeHolder="Contraseña del bot">
                        <input type="submit" class="avion imagen boton borde"  >
                    </form>');
                    }
                ?>
                    
                </div>
            </div>
        </div>
        <div id="footer">
            <a href="servicios.php" class="campana imagen izquierda">Servicios</a>
            <a href="turnos.php" class="logoboton imagen centro">Turnos</a>
            <a href="inicio.php" class="flecha imagen derecha">Volver</a>
        </div>
    </div>
    
    <style>
      
    </style>
</body>
</html>

<script src="codigojs/sombra.js"></script>
<script src="codigojs/volveratras.js"></script>
