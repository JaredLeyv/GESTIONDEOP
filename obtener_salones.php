<?php
require 'conexion.php';

if (isset($_GET['edificio_id'])) {
    $edificio_id = $_GET['edificio_id'];
    $salones_query = "SELECT id, nombre FROM salones WHERE edificio_id = $edificio_id";
    $salones_result = mysqli_query($conn, $salones_query);

    echo "<option value=''>Seleccionar Salón</option>";
    if (mysqli_num_rows($salones_result) > 0) {
        while($row = mysqli_fetch_assoc($salones_result)) {
            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
        }
    } else {
        echo "<option value=''>No hay salones disponibles</option>";
    }
}
?>
