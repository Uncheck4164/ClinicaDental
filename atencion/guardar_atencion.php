<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $rut_paciente   = $_POST['rut_paciente'];
    $id_tratamiento = $_POST['id_tratamiento'];
    $id_formapago   = $_POST['id_formapago'];
    $fecha          = $_POST['fecha'];

    $sql = "INSERT INTO Atencion (Rut_Paciente, ID_Tratamiento, ID_FormaPago, Fecha) VALUES (?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("siis", $rut_paciente, $id_tratamiento, $id_formapago, $fecha);

    if ($stmt->execute()) {
        header("Location: listar_atenciones.php?status=create");
        exit();
    } else {
        echo "Error al registrar la atención: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "Acceso no permitido.";
}

$conexion->close();
?>