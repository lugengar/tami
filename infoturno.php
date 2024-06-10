<?php
// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

require "codigophp/conexionbs.php";

// Función para actualizar el estado del turno
if (isset($_POST['cancelar_turno'])) {
    $turno_id = $_POST['turno_id'];
    $update_sql = "UPDATE turnos SET estado = 'cancelado' WHERE turno_id = $turno_id";
    $conn->query($update_sql);
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
                                        echo '<div class="rectangulo2">
                                                <h1>'.$row["fecha"]." ".$row["horario"].'</h1> 
                                                <p>'.$row["nombre_cliente"].'</p>
                                                <p class="estado">Estado: '.$row["estado"].'</p>';
                                        if ($row["estado"] == "agendado") {
                                            echo '<button class="imagen basura" onclick="mostrarConfirmacion('.$row["turno_id"].')"></button>';
                                        }
                                        echo '</div>';
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

    <!-- Modal de confirmación -->
    <div id="modalConfirmacion" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarConfirmacion()">&times;</span>
            <p>¿Está seguro que quiere cancelar este turno?</p>
            <form method="POST">
                <input type="hidden" name="turno_id" id="turno_id">
                <button type="submit" name="cancelar_turno">Sí, cancelar</button>
                <button type="button" onclick="cerrarConfirmacion()">No, volver</button>
            </form>
        </div>
    </div>

    <script src="codigojs/sombra.js"></script>
    <script>
        function mostrarConfirmacion(turno_id) {
            document.getElementById('turno_id').value = turno_id;
            document.getElementById('modalConfirmacion').style.display = 'block';
        }

        function cerrarConfirmacion() {
            document.getElementById('modalConfirmacion').style.display = 'none';
        }
    </script>
</body>
</html>
