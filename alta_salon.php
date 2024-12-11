<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Salón</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Alta de Salón</h1>
</header>

<main>
    <?php
        include 'conexion.php';

        // Obtener edificios para el formulario
        $edificios_query = "SELECT id, nombre FROM edificios";
        $edificios_result = mysqli_query($conn, $edificios_query);
    ?>

    <section class="section">
        <h2>Dar de Alta Salón</h2>
        <form method="post" action="">
            <label for="nombreSalon">Nombre del Salón:</label>
            <input type="text" id="nombreSalon" name="nombreSalon" required>

            <label for="edificio">Edificio:</label>
            <select id="edificio" name="edificio" required>
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

            <button type="submit" name="altaSalon">Crear Salón</button>
        </form>

        <?php
            // Alta de salones
            if (isset($_POST['altaSalon'])) {
                $nombreSalon = $_POST['nombreSalon'];
                $edificio = $_POST['edificio'];

                $sql = "INSERT INTO salones (nombre, edificio_id) VALUES ('$nombreSalon', '$edificio')";
                if (mysqli_query($conn, $sql)) {
                    echo "Salón creado exitosamente.";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        ?>
    </section>
</main>

</body>
</html>
