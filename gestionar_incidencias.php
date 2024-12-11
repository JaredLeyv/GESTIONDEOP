<?php
session_start();
include 'conexion.php';

// ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Incidencias</title>
    <style>
        /* Estilos básicos */
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
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px;
            background: #ccc;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .tab.active {
            background: #999;
            font-weight: bold;
        }
        .tab:hover {
            background: #bbb;
        }
        .content {
            display: none;
        }
        .content.active {
            display: block;
        }
    </style>
</head>
<body>

<header>
    <h1>Gestionar Incidencias</h1>
</header>

<main>

    <div class="tabs">
        <div class="tab active" data-content="sin_resolver">Incidencias Sin Resolver</div>
        <div class="tab" data-content="resueltas">Incidencias Resueltas</div>
    </div>

    <!-- Incidencias Sin Resolver -->
    <div class="content active" id="sin_resolver">
        <h2>Incidencias Sin Resolver</h2>
        <?php
        // Obtener incidencias sin resolver
        $query_sin_resolver = "SELECT i.id, i.descripcion, i.fecha_reporte, i.tecnico_asignado, d.id AS dispositivo_id, 
                               d.nombre AS dispositivo, c.nombre AS usuario
                               FROM incidencias i
                               JOIN dispositivos d ON i.dispositivo_id = d.id
                               JOIN cuentas c ON i.usuario_id = c.id
                               WHERE i.estado = 'sin resolver'";
        $result_sin_resolver = mysqli_query($conn, $query_sin_resolver);

        if (mysqli_num_rows($result_sin_resolver) > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha de Reporte</th>
                        <th>Dispositivo</th>
                        <th>Usuario</th>
                        <th>Técnico</th>
                        <th>Acción</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result_sin_resolver)) {
                $tecnico_asignado = $row['tecnico_asignado'] ? "Aceptada por Técnico ID: {$row['tecnico_asignado']}" : "No asignado";
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['fecha_reporte']}</td>
                        <td>{$row['dispositivo']}</td>
                        <td>{$row['usuario']}</td>
                        <td>$tecnico_asignado</td>
                        <td>";
                if (!$row['tecnico_asignado']) {
                    echo "<form method='post' action='gestionar_incidencias.php'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='aceptar'>Aceptar</button>
                          </form>";
                } else {
                    echo "<form method='get' action='resolver_incidencia.php'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='hidden' name='dispositivo_id' value='{$row['dispositivo_id']}'>
                            <button type='submit'>Resolver</button>
                          </form>";
                }
                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay incidencias sin resolver.</p>";
        }
        ?>
    </div>

    <!-- Incidencias Resueltas -->
    <div class="content" id="resueltas">
        <h2>Incidencias Resueltas</h2>
        <?php
        // Obtener incidencias resueltas
        $query_resueltas = "SELECT i.id, i.descripcion, i.fecha_reporte, i.fecha_resolucion, i.cambios, d.nombre AS dispositivo, c.nombre AS usuario
                            FROM incidencias i
                            JOIN dispositivos d ON i.dispositivo_id = d.id
                            JOIN cuentas c ON i.usuario_id = c.id
                            WHERE i.estado = 'resuelta'";
        $result_resueltas = mysqli_query($conn, $query_resueltas);

        if (mysqli_num_rows($result_resueltas) > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha de Reporte</th>
                        <th>Fecha de Resolución</th>
                        <th>Dispositivo</th>
                        <th>Usuario</th>
                        <th>Cambios</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result_resueltas)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['fecha_reporte']}</td>
                        <td>{$row['fecha_resolucion']}</td>
                        <td>{$row['dispositivo']}</td>
                        <td>{$row['usuario']}</td>
                        <td>{$row['cambios']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay incidencias resueltas.</p>";
        }
        ?>
    </div>

    <!-- PHP para aceptar incidencias -->
    <?php
    if (isset($_POST['aceptar'])) {
        $id = $_POST['id'];

        // Actualizar la incidencia asignando el técnico actual
        $update_query = "UPDATE incidencias SET tecnico_asignado = '$usuario_id' WHERE id = '$id'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Incidencia aceptada correctamente.'); window.location.href='gestionar_incidencias.php';</script>";
        } else {
            echo "<script>alert('Error al aceptar la incidencia: " . mysqli_error($conn) . "');</script>";
        }
    }
    ?>

</main>

<script>
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.dataset.content).classList.add('active');
        });
    });
</script>

</body>
</html>
