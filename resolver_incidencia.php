<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar la resolución de la incidencia
    $id = $_POST['id']; // ID de la incidencia
    $dispositivo_id = $_POST['dispositivo_id'];
    $cambios = $_POST['cambios'];
    $nuevo_nombre = $_POST['nombre'];
    $nueva_marca = $_POST['marca'];
    $nuevo_estado = $_POST['estado'];

    // Obtener el estado actual del dispositivo
    $query_dispositivo = "SELECT nombre, marca, estado FROM dispositivos WHERE id = '$dispositivo_id'";
    $result_dispositivo = mysqli_query($conn, $query_dispositivo);
    $dispositivo_actual = mysqli_fetch_assoc($result_dispositivo);

    if ($dispositivo_actual) {
        $estado_anterior = "Nombre: {$dispositivo_actual['nombre']}, Marca: {$dispositivo_actual['marca']}, Estado: {$dispositivo_actual['estado']}";

        // Actualizar el dispositivo
        $update_dispositivo = "UPDATE dispositivos 
                               SET nombre = '$nuevo_nombre', marca = '$nueva_marca', estado = '$nuevo_estado' 
                               WHERE id = '$dispositivo_id'";
        if (mysqli_query($conn, $update_dispositivo)) {
            $estado_posterior = "Nombre: $nuevo_nombre, Marca: $nueva_marca, Estado: $nuevo_estado";

            // Marcar la incidencia como resuelta y registrar cambios
            $fecha_resolucion = date("Y-m-d");
            $update_incidencia = "UPDATE incidencias 
                                  SET estado = 'resuelta', 
                                      fecha_resolucion = '$fecha_resolucion', 
                                      cambios = CONCAT('Antes: $estado_anterior\nDespués: $estado_posterior\nCambios realizados: $cambios') 
                                  WHERE id = '$id'";
            if (mysqli_query($conn, $update_incidencia)) {
                echo "<script>alert('Incidencia resuelta exitosamente.'); window.location.href='gestionar_incidencias.php';</script>";
            } else {
                echo "<script>alert('Error al actualizar la incidencia.');</script>";
            }
        } else {
            echo "<script>alert('Error al actualizar el dispositivo.');</script>";
        }
    } else {
        echo "<script>alert('El dispositivo no existe.');</script>";
    }
} else {
    // Mostrar el formulario de resolución
    $id = $_GET['id'];
    $dispositivo_id = $_GET['dispositivo_id'];

    // Obtener información del dispositivo
    $query_dispositivo = "SELECT nombre, marca, estado FROM dispositivos WHERE id = '$dispositivo_id'";
    $result_dispositivo = mysqli_query($conn, $query_dispositivo);
    $dispositivo = mysqli_fetch_assoc($result_dispositivo);

    if ($dispositivo) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolver Incidencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        input, textarea, select, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Resolver Incidencia</h1>
    <form method="post" action="resolver_incidencia.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="dispositivo_id" value="<?php echo $dispositivo_id; ?>">

        <h2>Detalles del Dispositivo</h2>
        <p><strong>Nombre actual:</strong> <?php echo $dispositivo['nombre']; ?></p>
        <p><strong>Marca actual:</strong> <?php echo $dispositivo['marca']; ?></p>
        <p><strong>Estado actual:</strong> <?php echo $dispositivo['estado']; ?></p>

        <h2>Modificaciones</h2>
        <label for="nombre">Nuevo Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $dispositivo['nombre']; ?>" required>

        <label for="marca">Nueva Marca:</label>
        <input type="text" id="marca" name="marca" value="<?php echo $dispositivo['marca']; ?>" required>

        <label for="estado">Nuevo Estado:</label>
        <select id="estado" name="estado" required>
            <option value="1" <?php echo $dispositivo['estado'] == '1' ? 'selected' : ''; ?>>Activo</option>
            <option value="2" <?php echo $dispositivo['estado'] == '2' ? 'selected' : ''; ?>>Mantenimiento</option>
            <option value="3" <?php echo $dispositivo['estado'] == '3' ? 'selected' : ''; ?>>Baja</option>
        </select>

        <label for="cambios">Cambios Realizados:</label>
        <textarea id="cambios" name="cambios" rows="5" placeholder="Describe los cambios realizados para resolver la incidencia" required></textarea>

        <button type="submit">Guardar Cambios y Resolver</button>
    </form>
</body>
</html>
<?php
    } else {
        echo "<script>alert('El dispositivo no existe.'); window.location.href='gestionar_incidencias.php';</script>";
    }
}
?>
