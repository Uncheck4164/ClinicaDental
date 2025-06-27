<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "INSERT INTO Paciente (Rut, Nombre, Email) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $rut, $nombre, $email);

    if ($stmt->execute()) {
        echo "¡Paciente registrado con éxito!";
    } else {
        echo "Error al registrar el paciente: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Acceso no permitido.";
}
?>