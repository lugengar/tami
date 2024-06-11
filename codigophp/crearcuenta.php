<?php
// register.php
session_start();
include "conexionbs.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $correo = $_POST['correo'];

    // Validar la entrada
    if (empty($username) || empty($password) || empty($correo)) {
        die('Por favor, complete todos los campos.');
    }
    $sql = "SELECT * FROM usuario WHERE usuario.nombre = '".$username."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        die ("Nombre ya utilizado");
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $mysqli = $conn;

    // Insertar el nuevo usuario
    $stmt = $mysqli->prepare("INSERT INTO usuario (nombre, contraseña, correo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $correo);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        // Obtener el ID del usuario recién insertado
        $user_id = $stmt->insert_id;
        $_SESSION['id_usuario'] = $user_id; // Guardar el ID del usuario en la sesión
        $_SESSION['username'] = $username; // Guardar el ID del usuario en la sesión

        // Redirigir al formulario de configurar horarios
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error en el registro.";
    }

    $stmt->close(); 
    $mysqli->close();

    }
    // Hashear la contraseña
   
}
?>
