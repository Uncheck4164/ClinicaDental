<?php
require_once '../conexion.php';

if (isset($_GET['ID_FormaPago'])) {
    $ID_FormaPago_a_eliminar = $_GET['ID_FormaPago'];
    $sql = "DELETE FROM FormaPago WHERE ID_FormaPago = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    $stmt->bind_param("s", $ID_FormaPago_a_eliminar);
    if ($stmt->execute()) {
        header('Location: listar_pago.php=deleted');
        exit();
    } else {
        echo "Error al eliminar el metodo de pago: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "ID_FormaPago no especificado.";
    header('Location: listar_pago.php');
    exit();
}
?>