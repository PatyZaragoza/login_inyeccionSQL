/* Patricia Zaragoza Palma*/
<?php
$servidor = "localhost";
$usuario = "root";
$baseDatos = "login";
$password = "1234";

$conexion = mysqli_connect($servidor,$usuario,$password,$baseDatos);
if(!$conexion){
    echo "Error al conectar la BD!";
}

if(isset($_POST['txtemail']) && isset($_POST['txtcontraseña'])){
    $user = $_POST['txtemail'];
    $pass = $_POST['txtcontraseña'];

    include('conexion.php');

    $sql = "SELECT * FROM user WHERE usuario = '$user' AND contraseña = '$pass'";
    $ejecsql = mysqli_query($conexion, $sql);

    if($ejecsql){
        $regUser = mysqli_fetch_assoc($ejecsql);

        if($regUser){
            // Iniciar sesión 
            session_start();
            // Crear variables de sesión para los datos del usuario
            $_SESSION['usuario'] = $regUser['Nombre_Usuario'] . " " . $regUser['Apellido1'] . " " . $regUser['Apellido2'];
            $_SESSION['rol'] = $regUser['Id_Rol'];
            // Redirigir al usuario a index.php
            header("location:index.php");
            exit;
        } else {
            echo '<div class="container mt-3"><div class="alert alert-danger">Email o contraseña incorrectos.</div></div>';
        }
    } else {
        echo '<div class="container mt-3"><div class="alert alert-danger">Error en la consulta SQL.</div></div>';
    }

    mysqli_close($conexion);
}
?>

