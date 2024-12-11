<?php
require 'vendor/autoload.php'; // Cargar PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// Configurar conexión a MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "propietariosdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Cargar archivo Excel
$inputFileName = 'data.xlsx'; // Cambia por la ruta de tu archivo
$spreadsheet = IOFactory::load($inputFileName);

// Obtener datos de la primera hoja
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

foreach ($rows as $index => $row) {
    if ($index == 1) continue; // Saltar la cabecera
    $dni = $row['A'];
    $nombre = $row['B'];
    $departamento = $row['C'];
    $correo = $row['D'];
    $telefono = $row['E'];
    $estado_departamento = $row['F'];

    // Insertar en la tabla MySQL
    $stmt = $conn->prepare("INSERT INTO propietarios (dni, nombre, departamento, correo, telefono, estado_departamento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iss", $dni, $nombre,$departamento, $correo, $telefono, $estado_departamento);
    $stmt->execute();
}

echo "Datos importados exitosamente.";

$conn->close();
?>
