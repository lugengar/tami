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

    // Validar la entrada
    if (empty($username) || empty($password)) {
        die('Por favor, complete todos los campos.');
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $mysqli = $conn;

    // Insertar el nuevo usuario
    $stmt = $mysqli->prepare("INSERT INTO chatbot (nombre, contrasena, usuario_fk) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $_SESSION['id_usuario']);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        echo "Registro exitoso.";
    } else {
        echo "Error en el registro.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

