<?php
session_start();
include 'conexion.php';

// ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_problema'])) {
    $incidencia_id = $_POST['incidencia_id'];
    $causa_raiz = $_POST['causa_raiz'];
    $error_conocido = $_POST['error_conocido'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_resolucion = $_POST['fecha_resolucion'];
    $solucion = $_POST['solucion'];
    $necesita_rfc = isset($_POST['necesita_rfc']) ? 1 : 0;

    $query = "INSERT INTO errores_conocidos (incidencia_id, causa_raiz, error_conocido, fecha_ingreso, fecha_resolucion, solucion, necesita_rfc)
              VALUES ('$incidencia_id', '$causa_raiz', '$error_conocido', '$fecha_ingreso', '$fecha_resolucion', '$solucion', '$necesita_rfc')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Problema registrado correctamente.'); window.location.href='gestion_problemas.php';</script>";
    } else {
        echo "<script>alert('Error al registrar el problema: " . mysqli_error($conn) . "');</script>";
    }
}

// Obtener incidencias sin resolver
$query_incidencias = "SELECT id, descripcion FROM incidencias WHERE estado = 'sin resolver' ORDER BY id DESC";
$result_incidencias = mysqli_query($conn, $query_incidencias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Problemas</title>
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
        input[type="text"], textarea, input[type="date"] {
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
    <h1>Gestión de Problemas</h1>
</header>

<main>
    <h2>Incidencias Sin Resolver</h2>
    <?php
    if (mysqli_num_rows($result_incidencias) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result_incidencias)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['descripcion']}</td>
                    <td>
                        <form method='post' action='gestion_problemas.php'>
                            <input type='hidden' name='incidencia_id' value='{$row['id']}'>
                            <button type='submit' name='seleccionar_incidencia'>Seleccionar</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay incidencias sin resolver.</p>";
    }

    // Cargar los detalles de la incidencia seleccionada
    if (isset($_POST['seleccionar_incidencia'])) {
        $incidencia_id = $_POST['incidencia_id'];
        $query_incidencia = "SELECT * FROM incidencias WHERE id = '$incidencia_id'";
        $result_incidencia = mysqli_query($conn, $query_incidencia);
        $incidencia = mysqli_fetch_assoc($result_incidencia);
        ?>
        
        <h2>Registrar Problema</h2>
        <form method="post" action="gestion_problemas.php">
            <div class="form-group">
                <label for="incidencia">Incidencia:</label>
                <input type="text" id="incidencia" name="incidencia" value="<?php echo htmlspecialchars($incidencia['descripcion']); ?>" disabled>
                <input type="hidden" name="incidencia_id" value="<?php echo $incidencia['id']; ?>">
            </div>
            <div class="form-group">
                <label for="causa_raiz">Causa Raíz:</label>
                <textarea id="causa_raiz" name="causa_raiz" required></textarea>
            </div>
            <div class="form-group">
                <label for="error_conocido">Error Conocido:</label>
                <textarea id="error_conocido" name="error_conocido" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso:</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
            </div>
            <div class="form-group">
                <label for="fecha_resolucion">Fecha de Resolución:</label>
                <input type="date" id="fecha_resolucion" name="fecha_resolucion">
            </div>
            <div class="form-group">
                <label for="solucion">Solución:</label>
                <textarea id="solucion" name="solucion" required></textarea>
            </div>
            <div class="form-group">
                <label for="necesita_rfc">¿Necesita RFC?</label>
                <input type="checkbox" id="necesita_rfc" name="necesita_rfc">
            </div>
            <button type="submit" name="registrar_problema">Registrar Problema</button>
        </form>
        <?php
    }
    ?>

    <h2>Historial de Problemas Resueltos</h2>
    <?php
    // Obtener problemas resueltos
    $query_problemas_resueltos = "SELECT ek.id, ek.causa_raiz, ek.error_conocido, ek.fecha_ingreso, ek.fecha_resolucion, ek.solucion, ek.necesita_rfc, i.descripcion AS incidencia_descripcion 
                                  FROM errores_conocidos ek 
                                  JOIN incidencias i ON ek.incidencia_id = i.id 
                                  WHERE i.estado = 'resuelta' 
                                  ORDER BY ek.fecha_resolucion DESC";
    $result_problemas_resueltos = mysqli_query($conn, $query_problemas_resueltos);

    if (mysqli_num_rows($result_problemas_resueltos) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Incidencia</th>
                    <th>Causa Raíz</th>
                    <th>Error Conocido</th>
                    <th>Fecha de Ingreso</th>
                    <th>Fecha de Resolución</th>
                    <th>Solución</th>
                    <th>Necesita RFC</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result_problemas_resueltos)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['incidencia_descripcion']}</td>
                    <td>{$row['causa_raiz']}</td>
                    <td>{$row['error_conocido']}</td>
                    <td>{$row['fecha_ingreso']}</td>
                    <td>{$row['fecha_resolucion']}</td>
                    <td>{$row['solucion']}</td>
                    <td>" . ($row['necesita_rfc'] ? 'Sí' : 'No') . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay problemas resueltos.</p>";
    }
    ?>
</main>

</body>
</html>
