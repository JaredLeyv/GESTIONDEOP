<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Dispositivo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Alta de Dispositivo</h1>
</header>

<main>
    <?php
        include 'conexion.php';

        // Obtener salones para el formulario
        $salones_query = "SELECT id, nombre FROM salones";
        $salones_result = mysqli_query($conn, $salones_query);
    ?>

    <section class="section">
        <h2>Dar de Alta Dispositivo</h2>
        <form method="post" action="">
            <label for="nombreDispositivo">Nombre del Dispositivo:</label>
            <input type="text" id="nombreDispositivo" name="nombreDispositivo" required>

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" required>

            <label for="fechaAlta">Fecha de Alta:</label>
            <input type="date" id="fechaAlta" name="fechaAlta" required>

            <label for="componentes">Componentes:</label>
            <textarea id="componentes" name="componentes" rows="3"></textarea>

            <label for="estado">Estado del Dispositivo:</label>
            <select id="estado" name="estado" required>
                <option value="1">Activo</option>
                <option value="2">Mantenimiento</option>
                <option value="3">Baja</option>
            </select>

            <label for="salonDispositivo">Salón:</label>
            <select id="salonDispositivo" name="salonDispositivo" required>
                <option value="">Seleccionar Salón</option>
                <?php
                    if (mysqli_num_rows($salones_result) > 0) {
                        while($row = mysqli_fetch_assoc($salones_result)) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hay salones disponibles</option>";
                    }
                ?>
            </select>

            <button type="submit" name="altaDispositivo">Crear Dispositivo</button>
        </form>

        <?php
            // Alta de dispositivos
            if (isset($_POST['altaDispositivo'])) {
                $nombreDispositivo = $_POST['nombreDispositivo'];
                $marca = $_POST['marca'];
                $fechaAlta = $_POST['fechaAlta'];
                $componentes = $_POST['componentes'];
                $estado = $_POST['estado'];
                $salonDispositivo = $_POST['salonDispositivo'];

                $sql = "INSERT INTO dispositivos (nombre, marca, fecha_alta, componentes, estado, salon_id) 
                        VALUES ('$nombreDispositivo', '$marca', '$fechaAlta', '$componentes', '$estado', '$salonDispositivo')";
                if (mysqli_query($conn, $sql)) {
                    echo "Dispositivo creado exitosamente.";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        ?>
    </section>
</main>

</body>
</html>
