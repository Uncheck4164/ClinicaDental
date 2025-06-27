<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_atencion    = (int)$_POST['id_atencion'];
    $rut_paciente   = $_POST['rut_paciente'];
    $id_tratamiento = (int)$_POST['id_tratamiento'];
    $id_formapago   = (int)$_POST['id_formapago'];
    $fecha          = $_POST['fecha'];

    $sql = "UPDATE Atencion SET 
                RUT_Paciente = ?, 
                ID_Tratamiento = ?, 
                ID_FormaPago = ?, 
                Fecha = ? 
            WHERE 
                ID_Atencion = ?";

    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die('Error al preparar la consulta de actualización: ' . $conexion->error);
    }
    
    $stmt->bind_param("siisi", $rut_paciente, $id_tratamiento, $id_formapago, $fecha, $id_atencion);

    if ($stmt->execute()) {
        header("Location: listar_atenciones.php?status=success");
        exit();
    } else {
        die("Error al actualizar la atención: " . $stmt->error);
    }

    $stmt->close();

} else {
    echo "Acceso no permitido.";
}

$conexion->close();
?>