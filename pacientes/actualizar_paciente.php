<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "UPDATE Paciente SET Nombre = ?, Email = ? WHERE Rut = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta de actualización: " . $conexion->error);
    }
    
    $stmt->bind_param("sss", $nombre, $email, $rut);
    
    if ($stmt->execute()) {
        header('Location: listar_pacientes.php?status=success');
        exit();
    } else {
        echo "Error al actualizar el paciente: " . $stmt->error;
    }
    
    $stmt->close();
    $conexion->close();

} else {
    header('Location: listar_pacientes.php');
    exit();
}
?>