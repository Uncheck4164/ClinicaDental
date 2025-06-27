<?php
require_once '../conexion.php';

// 2. Comprobar que hemos recibido el id_tratamiento por la URL (método GET)
if (isset($_GET['id_tratamiento'])) {
    
    $id_tratamiento_a_eliminar = $_GET['id_tratamiento'];

    $sql = "DELETE FROM Tratamiento WHERE ID_Tratamiento = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("i", $id_tratamiento_a_eliminar);
    
    if ($stmt->execute()) {
        header('Location: listar_tratamientos.php?status=deleted');
        exit();
    } else {
        echo "Error al eliminar el tratamiento: " . $stmt->error;
    }
    
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien llega a este archivo sin un id, lo redirigimos
    echo "ID de tratamiento no especificado.";
    header('Location: listar_tratamientos.php');
    exit();
}
?>