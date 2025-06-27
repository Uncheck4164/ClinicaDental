<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID_FormaPago = $_POST['ID_FormaPago'];
    $Descripcion = $_POST['Descripcion'];

    $sql = "UPDATE FormaPago SET Descripcion = ? WHERE ID_FormaPago = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta de actualización: " . $conexion->error);
    }

    $stmt->bind_param("ss", $Descripcion, $ID_FormaPago);

    if ($stmt->execute()) {
        header('Location: listar_pago.php?status=success');
        exit();
    } else {
        echo "Error al actualizar el metodo de Pago: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    header('Location: listar_pago.php');
    exit();
}
?>