<?php
// 1. Incluir el archivo de conexión
require_once '../conexion.php';

// 2. Verificar que se ha proporcionado un ID por GET
if (isset($_GET['id'])) {
    
    // 3. Validar el ID (convertirlo a entero para seguridad)
    $id_atencion = (int)$_GET['id'];

    // 4. Preparar la consulta SQL para eliminar la atención
    $sql = "DELETE FROM Atencion WHERE ID_Atencion = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die('Error al preparar la consulta: ' . $conexion->error);
    }
    
    // 5. Vincular el ID a la consulta
    $stmt->bind_param("i", $id_atencion);

    // 6. Ejecutar y verificar
    if ($stmt->execute()) {
        // Si la eliminación es exitosa, redirigir al listado con un mensaje de éxito
        header("Location: listar_atenciones.php?status=deleted");
        exit();
    } else {
        // Si hay un error, mostrarlo
        die("Error al eliminar la atención: " . $stmt->error);
    }

    $stmt->close();
    
} else {
    // Si no se proporcionó un ID, redirigir al listado
    header("Location: listar_atenciones.php?status=error");
    exit();
}

$conexion->close();
?>