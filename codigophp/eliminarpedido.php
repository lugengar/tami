<?php

// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

include "conexionbs.php";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del objeto
    $id = $_POST["id"];
    
    // Preparar y ejecutar la consulta SQL para eliminar el objeto
    $sql = "DELETE FROM servicios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "El objeto con ID $id ha sido eliminado correctamente.";
    } else {
        echo "Error al eliminar el objeto: " . $conn->error;
    }
    
    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
    header("Location: ../servicios.php");
    exit;
}
?>