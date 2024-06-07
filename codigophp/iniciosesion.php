<?php
// login.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Conectar a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "tami");

    if ($mysqli->connect_error) {
        die("Conexión fallida: " . $mysqli->connect_error);
    }

    // Buscar el usuario
    $stmt = $mysqli->prepare("SELECT id, contraseña FROM usuario WHERE nombre = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {

        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Iniciar sesión exitosa
    
            $sql = "SELECT * FROM horarios WHERE usuario_fk = $id";
            $result = $mysqli->query($sql);
            $_SESSION['id_usuario'] = $id;
            $_SESSION['username'] = $username;
            header("Location: ../inicio.php");
            echo "Contraseña correcta.";
            if ($result->num_rows = 0) {
                header("Location: ../inicio.php");
            }else{
                header("Location: ../pasoshorario.php");
            }
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Nombre de usuario no encontrado.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

