<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Edificio</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Alta de Edificio</h1>
</header>

<main>
    <?php
        include 'conexion.php';

        // Obtener facultades para el formulario
        $facultades_query = "SELECT id, nombre FROM facultades";
        $facultades_result = mysqli_query($conn, $facultades_query);
    ?>

    <section class="section">
        <h2>Dar de Alta Edificio</h2>
        <form method="post" action="">
            <label for="nombreEdificio">Nombre del Edificio:</label>
            <input type="text" id="nombreEdificio" name="nombreEdificio" required>
            
            <label for="facultad">Facultad:</label>
            <select id="facultad" name="facultad" required>
                <option value="">Seleccionar Facultad</option>
                <?php
                    if (mysqli_num_rows($facultades_result) > 0) {
                        while($row = mysqli_fetch_assoc($facultades_result)) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hay facultades disponibles</option>";
                    }
                ?>
            </select>
            
            <button type="submit" name="altaEdificio">Crear Edificio</button>
        </form>

        <?php
            // Alta de edificios
            if (isset($_POST['altaEdificio'])) {
                $nombreEdificio = $_POST['nombreEdificio'];
                $facultad = $_POST['facultad'];

                $sql = "INSERT INTO edificios (nombre, facultad_id) VALUES ('$nombreEdificio', '$facultad')";
                if (mysqli_query($conn, $sql)) {
                    echo "Edificio creado exitosamente.";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        ?>
    </section>
</main>

</body>
</html>
