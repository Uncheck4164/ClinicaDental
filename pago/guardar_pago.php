<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Descripcion = $_POST['descripcion'];
    $sql = "INSERT INTO FormaPago (Descripcion) VALUES (?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $Descripcion);
    if ($stmt->execute()) {
        echo "¡Metodo de Pago registrado con éxito!";
        header("Location: listar_pago.php");
    } else {
        echo "Error al registrar el metodo de Pago: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso no permitido.";
}
?>