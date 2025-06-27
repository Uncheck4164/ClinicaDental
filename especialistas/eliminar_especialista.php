<?php
require_once '../conexion.php';

if (isset($_GET['id_especialista'])) {
    $id_especialista_a_eliminar = $_GET['id_especialista'];
    $sql = "DELETE FROM Especialista WHERE id_especialista = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    $stmt->bind_param("s", $id_especialista_a_eliminar);
    if ($stmt->execute()) {
        header('Location: listar_especialistas.php=deleted');
        exit();
    } else {
        echo "Error al eliminar el especialista: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "id_especialista no especificado.";
    header('Location: listar_especialistas.php');
    exit();
}
?>