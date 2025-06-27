<?php
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Verificar que se accede por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Recibir y validar los datos del formulario
    $id_atencion    = (int)$_POST['id_atencion'];
    $rut_paciente   = $_POST['rut_paciente'];
    $id_tratamiento = (int)$_POST['id_tratamiento'];
    $id_formapago   = (int)$_POST['id_formapago'];
    $fecha          = $_POST['fecha'];

    // 4. Preparar la consulta SQL de ACTUALIZACIÓN (UPDATE)
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
    
    // 5. Vincular los parámetros (¡EL ORDEN IMPORTA!)
    // Tipos: s (string), i (integer), i (integer), s (string), i (integer)
    $stmt->bind_param("siisi", $rut_paciente, $id_tratamiento, $id_formapago, $fecha, $id_atencion);

    // 6. Ejecutar y verificar
    if ($stmt->execute()) {
        // Si la actualización es exitosa, redirigir al listado
        header("Location: listar_atenciones.php?status=updated");
        exit();
    } else {
        // Si hay un error, mostrarlo
        die("Error al actualizar la atención: " . $stmt->error);
    }

    $stmt->close();

} else {
    // Si no se accede por POST, negar el acceso
    echo "Acceso no permitido.";
}

$conexion->close();
?>