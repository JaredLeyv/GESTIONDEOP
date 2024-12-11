<?php
    $servername = "localhost:3307";
    $database = "siges3";
    $username = "root";
    $password = "";

    // Crear conexión
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Verificar conexión
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }
?>
