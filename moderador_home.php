<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es moderador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 2) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Moderador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
</header>

<main>
    <section class="section">
        <h2>Panel de Control - Moderador</h2>
        <ul>
            <li><a href="gestionar_incidencias.php">Gestionar Incidencias</a></li>
        </ul>
    </section>
</main>

</body>
</html>
