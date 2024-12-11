<?php
require 'conexion.php';

if (isset($_GET['salon_id'])) {
    $salon_id = $_GET['salon_id'];
    $dispositivos_query = "SELECT id, nombre FROM dispositivos WHERE salon_id = $salon_id";
    $dispositivos_result = mysqli_query($conn, $dispositivos_query);

    echo "<option value=''>Seleccionar Dispositivo</option>";
    if (mysqli_num_rows($dispositivos_result) > 0) {
        while($row = mysqli_fetch_assoc($dispositivos_result)) {
            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
        }
    } else {
        echo "<option value=''>No hay dispositivos disponibles</option>";
    }
}
?>
