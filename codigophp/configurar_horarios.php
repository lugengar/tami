<?php
// configurar_horarios.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id_usuario'])) {
        die("Error: No se ha iniciado sesión.");
    }

    $usuario_fk = $_SESSION['id_usuario']; // Usar el ID del usuario de la sesión

    $dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];
    $horarios = [];
    $dias_no_laborales = [];

    foreach ($dias as $dia) {
        if (isset($_POST["{$dia}_no_laboral"])) {
            $dias_no_laborales[] = $dia;
        } else {
            $inicio = $_POST["{$dia}_inicio"];
            $fin = $_POST["{$dia}_fin"];
            if (!empty($inicio) && !empty($fin)) {
                $horarios[$dia] = [$inicio, $fin];
            }
        }
    }

    // Conectar a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "tami");

    if ($mysqli->connect_error) {
        die("Conexión fallida: " . $mysqli->connect_error);
    }

    // Preparar los datos para la consulta
    $horarios_json = json_encode($horarios);
    $dias_no_laborales_json = json_encode($dias_no_laborales);

    // Insertar o actualizar los horarios del usuario
    $stmt = $mysqli->prepare("INSERT INTO horarios (usuario_fk, horarios, dias_no_laborales) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE horarios = VALUES(horarios), dias_no_laborales = VALUES(dias_no_laborales)");
    $stmt->bind_param("sss", $usuario_fk, $horarios_json, $dias_no_laborales_json);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirigir a la configuración de servicios
        header("Location: configurar_servicios.php");
        exit;
    } else {
        echo "Error al guardar los horarios o no se realizaron cambios.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Horarios</title>
    <link rel="stylesheet" href="../estiloscss/crearcuenta.css">
</head>
<body>
    <form class="contenedor-login" action="configurar_horarios.php" method="post">
        <img src="../imagenes/logogrande.png" alt="Logo" class="logo">
        <img src="../imagenes/user.png" alt="Foto de Usuario" class="foto-usuario">
        
        <h2>Configurar Horarios</h2>
        
        <?php
        $dias_semana = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];
        foreach ($dias_semana as $dia) {
            echo '<div class="dia-horario">';
            echo '<label for="' . $dia . '">' . ucfirst($dia) . ':</label>';
            echo '<input type="time" name="' . $dia . '_inicio" id="' . $dia . '_inicio">';
            echo '<input type="time" name="' . $dia . '_fin" id="' . $dia . '_fin">';
            echo '<label><input type="checkbox" name="' . $dia . '_no_laboral" id="' . $dia . '_no_laboral"> No laboral</label>';
            echo '</div>';
        }
        ?>
        
        <input type="submit" value="Guardar Horarios" class="boton-registrarse">
    </form>
</body>
</html>
