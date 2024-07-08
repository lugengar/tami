<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}
include "codigophp/conexionbs.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="estiloscss/styles.css">
    <link rel="stylesheet" href="estiloscss/imagenes.css">
    <link rel="stylesheet" href="estiloscss/animaciones.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/imagenes/userhd.png">
</head>
<body>
    <div id="pagina">
    <div id="subheader">
            <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p></p>
        </div>
        <div id="header">
            <a href="inicio.php" class="logo imagen" ></a>
            <button class="usuario imagen" id="user"></button>
        </div>
        
        <div id="contenido">
            <div class="contenido2">
                <div class="con3" id="inicio">
                    <h1>TURNOS MAS RECIENTES</h1>
                    <div class="scroll-y" style="height: 100%;">
                        <div class="conscroll-y">
                            <?php
                                $sql = "SELECT * FROM turnos WHERE vendedor_fk = ".$_SESSION['id_usuario'];
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo '<div class="rectangulo2"><h1>'.$row["fecha"]." ".$row["horario"].'</h1> <p>'.$row["nombre_cliente"].'</p> <button class="imagen"></button></div>';
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
            <a href="servicios.php" class="campana imagen izquierda">Servicios</a>
            <a href="turnos.php" class="logoboton imagen centro">Turnos</a>
            <a href="chatbot.php" class="chatbot imagen derecha">Chatbot</a>
        </div>
    
    <div id="sombra2" class="sombra">
        <div class="contenidosombra">
            <button class="barra" id="opcionequis2">
                <div class="equis"></div>
                <div>Volver</div>
                <div></div>
            </button>
            <div class="contenido2">
                <div class="con3" id="inicio">
                    <div class="scroll-y" style="height: 100%; padding-top:2vh;">
                        <div class="conscroll-y">
                            <a href="codigophp/cerrarsesion.php" class="flecha imagen boton">Cerrar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>

<script src="./codigojs/sombra2.js"></script>
