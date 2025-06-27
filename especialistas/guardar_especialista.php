<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $sql = "INSERT INTO Especialista (nombre) VALUES (?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombre);
    if ($stmt->execute()) {
        echo "¡Especialista registrado con éxito!";
        header("Location: listar_especialistas.php");
    } else {
        echo "Error al registrar el especialista: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso no permitido.";
}
?>