<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require 'conexion.php';

// Obtener el ID de la facultad del usuario desde la sesión
$facultad_id = $_SESSION['facultad_id'];

// Obtener edificios filtrados por la facultad del usuario
$edificios_query = "SELECT id, nombre FROM edificios WHERE facultad_id = $facultad_id";
$edificios_result = mysqli_query($conn, $edificios_query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Incidencia</title>
    <link rel="stylesheet" href="estilos.css">
    <script>
        // JavaScript para cargar los salones y dispositivos dinámicamente
        function cargarSalones(edificioId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'obtener_salones.php?edificio_id=' + edificioId, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('salon').innerHTML = this.responseText;
                    document.getElementById('dispositivo').innerHTML = "<option value=''>Seleccionar Dispositivo</option>";
                }
            };
            xhr.send();
        }

        function cargarDispositivos(salonId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'obtener_dispositivos.php?salon_id=' + salonId, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('dispositivo').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>

<header>
    <h1>Reportar Incidencia</h1>
</header>

<main>
    <section class="section">
        <form method="post" action="">
            <label for="descripcion">Descripción de la Incidencia:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="edificio">Seleccionar Edificio:</label>
            <select id="edificio" name="edificio" onchange="cargarSalones(this.value)" required>
                <option value="">Seleccionar Edificio</option>
                <?php
                if (mysqli_num_rows($edificios_result) > 0) {
                    while($row = mysqli_fetch_assoc($edificios_result)) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                } else {
                    echo "<option value=''>No hay edificios disponibles</option>";
                }
                ?>
            </select>

            <label for="salon">Seleccionar Salón:</label>
            <select id="salon" name="salon" onchange="cargarDispositivos(this.value)" required>
                <option value="">Seleccionar Salón</option>
            </select>

            <label for="dispositivo">Seleccionar Dispositivo:</label>
            <select id="dispositivo" name="dispositivo" required>
                <option value="">Seleccionar Dispositivo</option>
            </select>

            <button type="submit" name="reportarIncidencia">Reportar Incidencia</button>
        </form>

        <?php
        if (isset($_POST['reportarIncidencia'])) {
            $descripcion = $_POST['descripcion'];
            $dispositivo_id = $_POST['dispositivo'];
            $usuario_id = $_SESSION['usuario_id'];
            $fecha_reporte = date('Y-m-d');

            $sql = "INSERT INTO incidencias (descripcion, estado, fecha_reporte, dispositivo_id, usuario_id) 
                    VALUES ('$descripcion', 'sin resolver', '$fecha_reporte', '$dispositivo_id', '$usuario_id')";

            if (mysqli_query($conn, $sql)) {
                echo "Incidencia reportada exitosamente.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
    </section>
</main>

</body>
</html>
