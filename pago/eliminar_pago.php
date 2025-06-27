<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Comprobar que hemos recibido el id_especialista por la URL (método GET)
if (isset($_GET['ID_FormaPago'])) {
    
    $ID_FormaPago_a_eliminar = $_GET['ID_FormaPago'];

    // 3. Preparar la consulta SQL para eliminar (¡CON SENTENCIAS PREPARADAS!)
    // Es igual de importante usarlas para eliminar y actualizar, no solo para insertar.
    $sql = "DELETE FROM FormaPago WHERE ID_FormaPago = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
    
    // 4. Vincular el id_especialista a la consulta
    $stmt->bind_param("s", $ID_FormaPago_a_eliminar);
    
    // 5. Ejecutar y redirigir
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, redirigimos al usuario de vuelta a la lista
        header('Location: listar_pago.php');
        exit();
    } else {
        // Si hubo un error, lo mostramos
        echo "Error al eliminar el metodo de pago: " . $stmt->error;
    }
    
    // 6. Cerrar todo
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien llega a este archivo sin un ID_FormaPago, lo redirigimos
    echo "ID_FormaPago no especificado.";
    header('Location: listar_pago.php');
    exit();
}
?>