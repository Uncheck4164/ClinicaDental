<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Comprobar que hemos recibido el id_especialista por la URL (método GET)
if (isset($_GET['id_especialista'])) {
    
    $id_especialista_a_eliminar = $_GET['id_especialista'];

    // 3. Preparar la consulta SQL para eliminar (¡CON SENTENCIAS PREPARADAS!)
    // Es igual de importante usarlas para eliminar y actualizar, no solo para insertar.
    $sql = "DELETE FROM Especialista WHERE id_especialista = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    
    // 4. Vincular el id_especialista a la consulta
    $stmt->bind_param("s", $id_especialista_a_eliminar);
    
    // 5. Ejecutar y redirigir
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, redirigimos al usuario de vuelta a la lista
        header('Location: listar_especialistas.php');
        exit();
    } else {
        // Si hubo un error, lo mostramos
        echo "Error al eliminar el especialista: " . $stmt->error;
    }
    
    // 6. Cerrar todo
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien llega a este archivo sin un id_especialista, lo redirigimos
    echo "id_especialista no especificado.";
    header('Location: listar_especialistas.php');
    exit();
}
?>