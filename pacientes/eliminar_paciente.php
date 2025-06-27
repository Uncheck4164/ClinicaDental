<?php
require_once '../conexion.php';

if (isset($_GET['rut'])) {
    $rut_a_eliminar = $_GET['rut'];
    $sql = "DELETE FROM Paciente WHERE Rut = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    $stmt->bind_param("s", $rut_a_eliminar);
    if ($stmt->execute()) {
        header('Location: listar_pacientes.php?status=deleted');
        exit();
    } else {
        echo "Error al eliminar el paciente: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "RUT no especificado.";
    header('Location: listar_pacientes.php');
    exit();
}
?>