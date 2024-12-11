<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es un usuario regular
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 3) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // ID de la incidencia
    $calificacion = $_POST['calificacion']; // Calificación seleccionada

    // Validar que la calificación esté entre 1 y 5
    if ($calificacion >= 1 && $calificacion <= 5) {
        // Actualizar la calificación de la incidencia
        $update_query = "UPDATE incidencias SET calificacion = '$calificacion' WHERE id = '$id'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Calificación enviada exitosamente.'); window.location.href='usuario_home.php';</script>";
        } else {
            echo "<script>alert('Error al enviar la calificación.'); window.location.href='usuario_home.php';</script>";
        }
    } else {
        echo "<script>alert('Calificación inválida.'); window.location.href='usuario_home.php';</script>";
    }
} else {
    header("Location: usuario_home.php");
    exit;
}
?>
