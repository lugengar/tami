<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tami";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo(die("Conexión fallida: " . $conn->connect_error));
}

?>
