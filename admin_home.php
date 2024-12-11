<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 1) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="estilos.css">
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
        .section {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            margin-top: 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
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
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    <!-- Botón de Cerrar Sesión -->
    <form action="logout.php" method="post">
        <button type="submit">Cerrar Sesión</button>
    </form>
</header>

<main>
    <section class="section">
        <h2>Panel de Control - Administrador</h2>
        <ul>
            <li><a href="alta_edificio.php">Dar de Alta Edificio</a></li>
            <li><a href="alta_salon.php">Dar de Alta Salón</a></li>
            <li><a href="alta_dispositivo.php">Dar de Alta Dispositivo</a></li>
            <li><a href="gestionar_incidencias.php">Gestionar Incidencias</a></li>
            <li><a href="alta_usuario.php">Alta Usuarios</a></li>
            <li><a href="gestion_problemas.php">Gestión de Problemas</a></li> <!-- Nueva opción añadida -->
            <li><a href="ver_dispositivo.php">Configuraciones</a></li> <!-- Opción añadida para ver dispositivos -->
        </ul>
    </section>
</main>

</body>
</html>
