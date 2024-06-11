<?php
// configurar_servicios.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Error: No se ha iniciado sesión.");
}
include "conexionbs.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_fk = $_SESSION['id_usuario'];
    $nombre = trim($_POST['nombre']);
    $precio = trim($_POST['precio']);
    $descripcion = trim($_POST['descripcion']);
    $duracion = $_POST['duracion'];
    $estado = $_POST['estado'];

    // Validar la entrada
    if (empty($nombre) || empty($precio) || empty($descripcion) || empty($duracion) || empty($estado)) {
        die('Por favor, complete todos los campos.');
    }

    // Conectar a la base de datos
    $mysqli = $conn;

    // Insertar el nuevo servicio
    $stmt = $mysqli->prepare("INSERT INTO servicios (nombre, precio, descripcion, duracion, estado, usuario_fk) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $precio, $descripcion, $duracion, $estado, $usuario_fk);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirigir a la configuración de servicios
        header("Location: ../notificaciones.php");
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
    <title>Configurar Servicios</title>
    <link rel="stylesheet" href="../estiloscss/crearcuenta.css">
    <link rel="stylesheet" href="../estiloscss/imagenes.css">
</head>
<body style="background-color: #4139E6;">
    <form class="contenedor-login" action="configurar_servicios.php" method="post">
        <img  class="logo imagen">
        <img src="../imagenes/user.png" alt="Foto de Usuario" class="foto-usuario">

        <h2 style="color:white;">Agregar Servicio</h2>

        <input type="text" name="nombre" id="nombre" placeholder="Nombre del Servicio" required><br>
        <input type="text" name="precio" id="precio" placeholder="Precio" required><br>
        <textarea name="descripcion" id="descripcion" placeholder="Descripción" required></textarea><br>
        <input type="time" name="duracion" id="duracion" placeholder="Duración" required><br>
        <select name="estado" id="estado" required>
            <option value="disponible">Disponible</option>
            <option value="nodisponible">No Disponible</option>
        </select><br>
        <input type="submit" value="Agregar Servicio" class="boton-registrarse">
    </form>
</body>
</html>