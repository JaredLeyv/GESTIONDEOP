<?php
session_start();
include 'conexion.php';

// Obtener los valores del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar si existe el usuario
$query = "SELECT * FROM cuentas WHERE email = '$email' AND contraseña = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // El usuario existe
    $row = mysqli_fetch_assoc($result);
    
    // Guardar información del usuario en la sesión
    $_SESSION['usuario_id'] = $row['id'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['facultad_id'] = $row['facultad_id'];

    // Redirigir según el tipo de usuario
    if ($row['tipo'] == 1) {
        // Administrador
        header("Location: admin_home.php");
    } elseif ($row['tipo'] == 2) {
        // Moderador
        header("Location: gestionar_incidencias.php");
    } else {
        // Usuario regular
        header("Location: usuario_home.php");
    }
    exit;
} else {
    // Credenciales incorrectas, redirigir de vuelta al login con un mensaje de error
    header("Location: Index.php?error=1");
    exit;
}
?>
