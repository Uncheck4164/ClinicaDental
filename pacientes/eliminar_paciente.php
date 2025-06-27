<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Comprobar que hemos recibido el RUT por la URL (método GET)
if (isset($_GET['rut'])) {
    
    $rut_a_eliminar = $_GET['rut'];

    // 3. Preparar la consulta SQL para eliminar (¡CON SENTENCIAS PREPARADAS!)
    // Es igual de importante usarlas para eliminar y actualizar, no solo para insertar.
    $sql = "DELETE FROM Paciente WHERE Rut = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    
    // 4. Vincular el RUT a la consulta
    $stmt->bind_param("s", $rut_a_eliminar);
    
    // 5. Ejecutar y redirigir
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, redirigimos al usuario de vuelta a la lista
        header('Location: listar_pacientes.php');
        exit();
    } else {
        // Si hubo un error, lo mostramos
        echo "Error al eliminar el paciente: " . $stmt->error;
    }
    
    // 6. Cerrar todo
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien llega a este archivo sin un RUT, lo redirigimos
    echo "RUT no especificado.";
    header('Location: listar_pacientes.php');
    exit();
}
?>