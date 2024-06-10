<?php
// register.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validar la entrada
    if (empty($username) || empty($password)) {
        die('Por favor, complete todos los campos.');
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "tami");

    if ($mysqli->connect_error) {
        die("Conexión fallida: " . $mysqli->connect_error);
    }

    // Insertar el nuevo usuario
    $stmt = $mysqli->prepare("INSERT INTO usuario (nombre, contraseña) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        // Obtener el ID del usuario recién insertado
        $user_id = $stmt->insert_id;
        $_SESSION['id_usuario'] = $user_id; // Guardar el ID del usuario en la sesión

        // Redirigir al formulario de configurar horarios
        header("Location: configurar_horarios.php");
        exit;
    } else {
        echo "Error en el registro.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../estiloscss/crearcuenta.css">
</head>
<body>
    <form class="contenedor-login" action="./crearcuenta.php" method="post">
        <img src="imagenes/logogrande.png" alt="Logo" class="logo">
        <img src="imagenes/user.png" alt="Foto de Usuario" class="foto-usuario">

        <input type="text" class="boton-nombre" placeholder="Nombre de usuario" name="username" id="username" required><br>
        <input type="password" class="boton-iniciar" placeholder="Contraseña" name="password" id="password" required><br>
        <input type="submit" value="Registrarse" class="boton-registrarse">
    </form>
</body>
</html>
