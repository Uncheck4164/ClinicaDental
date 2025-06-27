<?php
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Comprobar que los datos llegan por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Recoger los datos del formulario
    // Es importante validar y sanitizar los datos, pero por simplicidad lo omitimos aquí.
    $ID_FormaPago = $_POST['ID_FormaPago'];
    $Descripcion = $_POST['Descripcion'];

    // 4. Preparar la consulta SQL para actualizar (¡CON SENTENCIAS PREPARADAS!)
    $sql = "UPDATE FormaPago SET Descripcion = ? WHERE ID_FormaPago = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta de actualización: " . $conexion->error);
    }
    
    // 5. Vincular los nuevos datos y el RUT a la consulta
    // 's' para string. El orden es importante: nombre, email, rut.
    $stmt->bind_param("ss", $Descripcion, $ID_FormaPago);
    
    // 6. Ejecutar y redirigir
    if ($stmt->execute()) {
        // Si la actualización fue exitosa, redirigimos al usuario a la lista
        header('Location: listar_pago.php?status=success');
        exit();
    } else {
        // Si hubo un error, lo mostramos
        echo "Error al actualizar el metodo de Pago: " . $stmt->error;
    }
    
    // 7. Cerrar todo
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien intenta acceder a este archivo directamente sin enviar datos por POST
    header('Location: listar_pago.php');
    exit();
}
?>