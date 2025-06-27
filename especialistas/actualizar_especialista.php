<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_especialista = $_POST['id_especialista'];
    $nombre = $_POST['nombre'];

    $sql = "UPDATE Especialista SET nombre = ? WHERE id_especialista = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta de actualización: " . $conexion->error);
    }

    $stmt->bind_param("ss", $nombre, $id_especialista);

    if ($stmt->execute()) {
        header('Location: listar_especialistas.php?status=success');
        exit();
    } else {
        echo "Error al actualizar el especialista: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    header('Location: listar_especialistas.php');
    exit();
}
?>