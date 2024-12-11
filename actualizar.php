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
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $estado_departamento = $_POST['estado_departamento'];
    $observaciones = $_POST['observaciones'];

    $query = "UPDATE propietarios SET telefono = ?, correo = ?, estado_departamento = ?, observaciones = ? WHERE dni = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $telefono, $correo, $estado_departamento, $observaciones, $dni);

    if ($stmt->execute()) {
        echo "<h3>Información actualizada correctamente.</h3>";
        echo "<a href='index.php'>Volver</a>";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
    $stmt->close();
}
?>
