<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Iniciar Sesión</h1>
</header>

<main>
    <section class="section">
        <h2>Por favor, ingresa tus datos</h2>
        <form method="post" action="validar_login.php">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Iniciar Sesión</button>
        </form>

        <?php
            // Mostrar un mensaje si hay un error
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>Credenciales incorrectas. Inténtalo de nuevo.</p>";
            }
        ?>
    </section>
</main>

</body>
</html>
