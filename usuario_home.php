<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es un usuario regular
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 3) {
    header("Location: login.php");
    exit;
}

// ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];

// Obtener incidencias resueltas pendientes de calificar
$query_incidencias = "SELECT i.id, i.descripcion, i.fecha_resolucion 
                      FROM incidencias i
                      WHERE i.usuario_id = '$usuario_id' 
                      AND i.estado = 'resuelta' 
                      AND i.calificacion IS NULL";
$result_incidencias = mysqli_query($conn, $query_incidencias);

if (!$result_incidencias) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Usuario</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .notificacion {
            background-color: #fffae6;
            border: 1px solid #ffe58a;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #856404;
        }
        .calificacion {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .calificacion button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .calificacion button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
</header>

<form action="logout.php" method="post">
    <button type="submit">Cerrar Sesión</button>
</form>

<main>
    <section class="section">
        <h2>Panel de Control - Usuario</h2>
        <ul>
            <li><a href="reportar_incidencia.php">Reportar Incidencia</a></li>
        </ul>

        <!-- Notificaciones -->
        <?php if (mysqli_num_rows($result_incidencias) > 0): ?>
            <div class="notificacion">
                <h3>Tienes incidencias resueltas pendientes de calificar:</h3>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result_incidencias)): ?>
                        <li>
                            <strong>Incidencia:</strong> <?php echo htmlspecialchars($row['descripcion']); ?> <br>
                            <strong>Resuelta el:</strong> <?php echo htmlspecialchars($row['fecha_resolucion']); ?>
                            <div class="calificacion">
                                <form method="post" action="calificar_incidencia.php">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <label for="calificacion_<?php echo $row['id']; ?>">Calificación:</label>
                                    <select id="calificacion_<?php echo $row['id']; ?>" name="calificacion" required>
                                        <option value="" disabled selected>Selecciona</option>
                                        <option value="1">1 estrella</option>
                                        <option value="2">2 estrellas</option>
                                        <option value="3">3 estrellas</option>
                                        <option value="4">4 estrellas</option>
                                        <option value="5">5 estrellas</option>
                                    </select>
                                    <button type="submit">Enviar Calificación</button>
                                </form>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else: ?>
            <p>No tienes incidencias pendientes de calificar.</p>
        <?php endif; ?>
    </section>
</main>

</body>
</html>
