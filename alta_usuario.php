<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        h2 {
            margin-top: 30px;
            color: #333;
        }
        .section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Dar de Alta Usuario</h1>
</header>

<main>
    <section class="section">
        <h2>Nuevo Usuario</h2>
        <form method="post" action="alta_usuario.php">
            <label for="nombre">Nombre del Usuario:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="tipo">Tipo de Usuario:</label>
            <select id="tipo" name="tipo" required>
                <option value="1">Administrador</option>
                <option value="2">Moderador</option>
                <option value="3">Usuario</option>
            </select>

            <div id="facultadField" style="display: none;">
                <label for="facultad">Facultad:</label>
                <select id="facultad" name="facultad">
                    <option value="">Seleccionar Facultad</option>
                    <?php
                    include 'conexion.php'; // Incluye el archivo de conexión
                    $facultades_query = "SELECT id, nombre FROM facultades";
                    $facultades_result = mysqli_query($conn, $facultades_query);
                    
                    if (mysqli_num_rows($facultades_result) > 0) {
                        while($row = mysqli_fetch_assoc($facultades_result)) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hay facultades disponibles</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="altaUsuario">Crear Usuario</button>
        </form>

        <?php
        // Procesar el formulario
        if (isset($_POST['altaUsuario'])) {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $tipo = $_POST['tipo'];
            $facultad = isset($_POST['facultad']) ? $_POST['facultad'] : null;

            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO cuentas (nombre, email, contraseña, tipo, facultad_id) VALUES ('$nombre', '$email', '$password', '$tipo', ".($facultad ? "'$facultad'" : "NULL").")";
            
            if (mysqli_query($conn, $sql)) {
                echo "Usuario creado exitosamente.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
    </section>
</main>

<script>
    // Mostrar u ocultar el campo de facultad según el tipo de usuario
    const tipoUsuarioSelect = document.getElementById('tipo');
    const facultadField = document.getElementById('facultadField');

    tipoUsuarioSelect.addEventListener('change', function() {
        if (this.value == '2' || this.value == '3') {
            facultadField.style.display = 'block';
        } else {
            facultadField.style.display = 'none';
        }
    });
</script>

</body>
</html>
