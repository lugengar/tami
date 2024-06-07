
<?php
// login.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['nombrebot']);
    $password = trim($_POST['contraseñabot']);

    // Conectar a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "tami");

    if ($mysqli->connect_error) {
        die("Conexión fallida: " . $mysqli->connect_error);
    }

    // Buscar el usuario
    $stmt = $mysqli->prepare("SELECT contrasena FROM chatbot WHERE nombre = ? AND usuario_fk = ?");
    $stmt->bind_param("ss", $username, $_SESSION['id_usuario']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Iniciar sesión exitosa
            echo "Contraseña correcta.";
            echo('
            <form id="auto-form" action="http://localhost:3000/submit-form" method="post" style="color:black;">
                <input type="password" value= "'.$_SESSION['id_usuario'].'" name ="id_usuario" style="background-color:black;" >
                <input type="submit" style="background-color:black;" >
            </form>

            <script>
                // Simula el envío automático del formulario al cargar la página
                window.onload = function() {
                    document.getElementById("auto-form").submit();
                };
            </script>
            ');
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

