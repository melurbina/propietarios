<?php
// Configuración de conexión
$host = "localhost";
$user = "root";
$password = "";
$dbname = "PropietariosDB";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta y Registro de Propietarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f7ff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1, h3 {
            text-align: center;
            color: #007acc;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input, select, textarea, button {
            margin-top: 5px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007acc;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #005f99;
        }
        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBqrm7T0ljuahfQWmfhpPWhUDZdZudHCxUjw&s" alt="Logo">
        </div>
        <h1>Consulta de Propietarios</h1>
        <form method="POST">
            <label for="dni">Ingrese su DNI:</label>
            <input type="text" id="dni" name="dni" maxlength="8" required>
            <button type="submit">Consultar</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = $_POST['dni'];

            // Consultar la base de datos
            $query = "SELECT * FROM propietarios WHERE dni = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h3>Información del Propietario</h3>";
                echo "<p><strong>D.N.I.:</strong> " . htmlspecialchars($row['dni']) . "</p>";
                echo "<p><strong>Nombre:</strong> " . htmlspecialchars($row['nombre']) . "</p>";
                echo "<p><strong>Departamento:</strong> " . htmlspecialchars($row['departamento']) . "</p>";
                echo "<p><strong>Correo Electrónico:</strong> " . htmlspecialchars($row['correo']) . "</p>";
                echo "<p><strong>Telefono:</strong> " . htmlspecialchars($row['telefono']) . "</p>";
                echo "<p><strong>Estado actual del Departamento:</strong> " . htmlspecialchars($row['estado_departamento']) . "</p>";

                    
               //Formulario para actualizar observaciones y datos
                echo '<h3>Use el campo observaciones para indicar alguna actualización como: Nuevo estado del departamento,  número de telefono, correo electronico, etc</h3>';
                echo '<form action="actualizar.php" method="POST">

                    <input type="hidden" name="dni" value="' . htmlspecialchars($row['dni']) . '">
                    <input type="hidden" name="nombre" value="' . htmlspecialchars($row['nombre']) . '">
                    <input type="hidden" name="departamento" value="' . htmlspecialchars($row['departamento']) . '">
                    <input type="hidden" name="correo" value="' . htmlspecialchars($row['correo']) . '">
                    <input type="hidden" name="telefono" value="' . htmlspecialchars($row['telefono']) . '">
                    <input type="hidden" name="estado_departamento" value="' . htmlspecialchars($row['estado_departamento']) . '">


                    <label for="observaciones">Observaciones: </label>
                    <textarea name="observaciones" rows="4" cols="50">' . htmlspecialchars($row['observaciones']) . '</textarea>
                    <button type="submit">Guardar / Salir </button>
                </form>';
            } else {
                echo "<h3>No se encontró información para el DNI ingresado. 
                Si es propietario de un departamento, regístrese utilizando los datos de su copia literal de registro de su departamento en SUNARP:</h3>";
                echo '<form action="registrar.php" method="POST">
                    <label for="dni">Documento Nacional de Identidad:</label>
                    <input type="text" name="dni" value="' . htmlspecialchars($dni) . '" readonly>
                    
                    <label for="nombre">Apellidos y Nombres:</label>
                    <input type="text" name="nombre" required>

                    <label for="departamento">Número de Departamento:</label>
                    <input type="text" name="departamento" required>

                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" name="correo" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" required>

                    <label for="estado_departamento">Estado del Departamento:</label>
                    <select name="estado_departamento" required>                        
                        <option value="Departamento sin habitar">Departamento sin habitar</option>
                        <option value="Departamento alquilado">Departamento alquilado</option>
                        <option value="Departamento habitado por el propietario">Departamento habitado por el propietario</option>
                    </select>
                    <button type="submit">Registrar</button>
                </form>';
            }
            $stmt->close();
        }
        ?>
    </div>
</body>
</html>
