<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 1) {
    header("Location: login.php");
    exit;
}

// Actualizar dispositivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_dispositivo'])) {
    $dispositivo_id = $_POST['dispositivo_id'];
    $componentes = $_POST['componentes'];
    $estado = $_POST['estado'];

    $query = "UPDATE dispositivos SET componentes='$componentes', estado='$estado' WHERE id='$dispositivo_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Dispositivo actualizado correctamente.'); window.location.href='ver_dispositivo.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el dispositivo: " . mysqli_error($conn) . "');</script>";
    }
}

// Obtener todos los dispositivos
$query_dispositivos = "SELECT * FROM dispositivos";
$result_dispositivos = mysqli_query($conn, $query_dispositivos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Dispositivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<header>
    <h1>Ver configuraciones</h1>
</header>

<main>
    <h2>Lista de Dispositivos</h2>
    <?php
    if (mysqli_num_rows($result_dispositivos) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Fecha de Alta</th>
                    <th>Componentes</th>
                    <th>Estado</th>
                    <th>Salón</th>
                    <th>G. de Cambios</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result_dispositivos)) {
            $estado = '';
            switch ($row['estado']) {
                case 1:
                    $estado = 'Activo';
                    break;
                case 2:
                    $estado = 'Mantenimiento';
                    break;
                case 3:
                    $estado = 'Baja';
                    break;
            }
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['marca']}</td>
                    <td>{$row['fecha_alta']}</td>
                    <td>{$row['componentes']}</td>
                    <td>{$estado}</td>
                    <td>{$row['salon_id']}</td>
                    <td>
                        <form method='post' action='ver_dispositivo.php'>
                            <input type='hidden' name='dispositivo_id' value='{$row['id']}'>
                            <button type='submit' name='editar'>Gestion de cambios</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay dispositivos registrados.</p>";
    }

    // Cargar detalles del dispositivo seleccionado para editar
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
        $dispositivo_id = $_POST['dispositivo_id'];
        $query_dispositivo = "SELECT * FROM dispositivos WHERE id = '$dispositivo_id'";
        $result_dispositivo = mysqli_query($conn, $query_dispositivo);
        $dispositivo = mysqli_fetch_assoc($result_dispositivo);
        ?>
        
        <h2>Editar Dispositivo</h2>
        <form method="post" action="ver_dispositivo.php">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($dispositivo['nombre']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($dispositivo['marca']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="fecha_alta">Fecha de Alta:</label>
                <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo htmlspecialchars($dispositivo['fecha_alta']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="componentes">Componentes:</label>
                <textarea id="componentes" name="componentes" required><?php echo htmlspecialchars($dispositivo['componentes']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="1" <?php if ($dispositivo['estado'] == 1) echo 'selected'; ?>>Activo</option>
                    <option value="2" <?php if ($dispositivo['estado'] == 2) echo 'selected'; ?>>Mantenimiento</option>
                    <option value="3" <?php if ($dispositivo['estado'] == 3) echo 'selected'; ?>>Baja</option>
                </select>
            </div>
            <div class="form-group">
                <label for="salon_id">Salón:</label>
                <input type="text" id="salon_id" name="salon_id" value="<?php echo htmlspecialchars($dispositivo['salon_id']); ?>" disabled>
            </div>
            <input type="hidden" name="dispositivo_id" value="<?php echo $dispositivo['id']; ?>">
            <button type="submit" name="actualizar_dispositivo">Actualizar Dispositivo</button>
        </form>
        <?php
    }
    ?>
</main>

</body>
</html>
