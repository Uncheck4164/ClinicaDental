<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
 
// 1. Incluir el archivo de conexión
require_once '../conexion.php';

// 2. Verificar que se ha accedido mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Recibir y guardar los datos del formulario en variables
    $nombre = $_POST['nombre'];

    // 4. Preparar la consulta SQL (Forma Segura)
    $sql = "INSERT INTO Especialista (nombre) VALUES (?)";
    $stmt = $conexion->prepare($sql);

    // 5. Vincular las variables a los marcadores de posición (?)
    // "ss" significa que los 2 valores son de tipo String (texto)
    $stmt->bind_param("s", $nombre);

    // 6. Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        echo "¡Especialista registrado con éxito!";
        header("Location: listar_especialistas.php");
    } else {
        echo "Error al registrar el especialista: " . $stmt->error;
    }

    // 7. Cerrar la sentencia y la conexión
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien intenta abrir este archivo directamente desde el navegador
    echo "Acceso no permitido.";
}
?>