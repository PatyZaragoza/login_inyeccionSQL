<?php
session_start(); // Iniciar sesión antes de cualquier salida

$servidor = "localhost";
$usuario = "root";
$baseDatos = "login";
$password = "1234";

$conexion = mysqli_connect($servidor, $usuario, $password, $baseDatos);

if (!$conexion) {
    die("Error al conectar la BD: " . mysqli_connect_error());
}

// Verifica si los campos del formulario están definidos
if (isset($_POST['txtemail']) && isset($_POST['txtcontraseña'])) {
    $user = mysqli_real_escape_string($conexion, $_POST['txtemail']);
    $pass = mysqli_real_escape_string($conexion, $_POST['txtcontraseña']);

    // Consulta segura con prepared statements
    $sql = "SELECT * FROM user WHERE usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $regUser = mysqli_fetch_assoc($result);

        // Verificar la contraseña (reemplaza con password_verify() si las contraseñas están hasheadas)
        if ($pass === $regUser['contraseña']) {  
            $_SESSION['usuario'] = $regUser['Nombre_Usuario'] . " " . $regUser['Apellido1'] . " " . $regUser['Apellido2'];
            $_SESSION['rol'] = $regUser['Id_Rol'];

            header("Location: index.php");
            exit;
        } else {
            echo '<div class="container mt-3"><div class="alert alert-danger">Email o contraseña incorrectos.</div></div>';
        }
    } else {
        echo '<div class="container mt-3"><div class="alert alert-danger">Usuario no encontrado.</div></div>';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>
