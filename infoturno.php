<?php
// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

require "codigophp/conexionbs.php";

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
    <link rel="stylesheet" href="estiloscss/calendario.css">
</head>
<body>
    <div id="pagina2">
        <div id="header">
            <a href="inicio.php" class="logo imagen"></a>
            <button class="usuario imagen"></button>
        </div>
        <div id="contenido3">
            <div class="contenido2">
                <div class="con3" id="inicio">
                <h1>TURNOS DEL <?php echo $_GET['fecha'];?></h1>
                    <div class="scroll-y" style="height: 100%;">
                        <div class="conscroll-y">
                            <?php
                                $sql = "SELECT * FROM turnos WHERE vendedor_fk = ".$_SESSION['id_usuario']." AND fecha = "."'".$_GET['fecha']."'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo '<div class="rectangulo2"><h1>'.$row["fecha"]." ".$row["horario"].'</h1> <p>'.$row["nombre_cliente"].'</p> <button class="imagen opciones"></button></div>';
                                    }
                                } else {
                                    echo "<h1>NO HAY TURNOS AUN</h1>";
                                }
                            ?>        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <a href="notificaciones.php" class="campana imagen izquierda">Notificaciones</a>
            <a href="turnos.php" class="flecha imagen centro">Volver</a>
            <a href="chatbot.php" class="chatbot imagen derecha">Chatbot</a>
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
                                <button onclick="console.log('hola')" class="ojo imagen boton">Ver detalles del turno</button>                  
                                <button onclick="console.log('hola')" class="basura imagen boton">Cancelar turno</button>
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
