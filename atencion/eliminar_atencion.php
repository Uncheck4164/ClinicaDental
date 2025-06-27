<?php
require_once '../conexion.php';

if (isset($_GET['id'])) {
    $id_atencion = (int)$_GET['id'];
    $sql = "DELETE FROM Atencion WHERE ID_Atencion = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die('Error al preparar la consulta: ' . $conexion->error);
    }
    $stmt->bind_param("i", $id_atencion);
    if ($stmt->execute()) {
        header("Location: listar_atenciones.php?status=deleted");
        exit();
    } else {
        die("Error al eliminar la atención: " . $stmt->error);
    }
    $stmt->close();
} else {
    header("Location: listar_atenciones.php?status=error");
    exit();
}
$conexion->close();
?>