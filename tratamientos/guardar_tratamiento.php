<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $valor = $_POST['valor'];
    $id_especialista = $_POST['id_especialista'];

    $sql = "INSERT INTO Tratamiento (Nombre, Valor, ID_Especialista) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sdi", $nombre, $valor, $id_especialista);

    if ($stmt->execute()) {
        header("Location: listar_tratamientos.php?status=create");
    } else {
        echo "Error al registrar el tratamiento: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Acceso no permitido.";
}
?>