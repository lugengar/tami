<?php

// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

include "conexionbs.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['nombrebot']);
    $password = trim($_POST['contraseñabot']);
    $telefono = trim($_POST['telefonobot']);
    $id_usuario = $_SESSION['id_usuario'];
    $telefono = "549".$telefono;
    // Validar la entrada
    if (empty($username) || empty($password) || empty($telefono)) {
        die('Por favor, complete todos los campos.');
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $mysqli = $conn;

    // Insertar el nuevo usuario
    $stmt = $mysqli->prepare("INSERT INTO chatbot (nombre, contraseña, usuario_fk) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssi", $username, $password, $id_usuario);
        if ($stmt->execute()) {
            echo "Registro exitoso.";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta de inserción: " . $mysqli->error;
    }

    // Actualizar el teléfono del usuario
    $stmt = $mysqli->prepare("UPDATE usuario SET telefono = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $telefono, $id_usuario);
        if ($stmt->execute()) {
            echo "El teléfono se ha actualizado correctamente.";
        } else {
            echo "Error al actualizar el teléfono: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta de actualización: " . $mysqli->error;
    }

    // Cerrar la conexión
    $mysqli->close();
    header("Location: ../chatbot.php");
    exit;
}
?>
