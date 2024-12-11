<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "PropietariosDB";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $departamento = $_POST['departamento'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $estado_departamento = $_POST['estado_departamento'];

    $query = "INSERT INTO propietarios (dni, nombre, departamento, correo, telefono, estado_departamento) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $dni, $nombre, $departamento, $correo, $telefono, $estado_departamento);

    if ($stmt->execute()) {
        echo "<h3>Sus datos han sigo registrados exitosamente.</h3>";
        echo "<a href='index.php'>Volver a la consulta</a>";
    } else {
        echo "Error en el registro: " . $conn->error;
    }
    $stmt->close();
}
?>
