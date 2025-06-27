<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
 
// 1. Incluir el archivo de conexión
require_once '../conexion.php';

// 2. Verificar que se ha accedido mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Recibir y guardar los datos del formulario en variables
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // 4. Preparar la consulta SQL (Forma Segura)
    $sql = "INSERT INTO Paciente (Rut, Nombre, Email) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    // 5. Vincular las variables a los marcadores de posición (?)
    // "sss" significa que los 3 valores son de tipo String (texto)
    $stmt->bind_param("sss", $rut, $nombre, $email);

    // 6. Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        echo "¡Paciente registrado con éxito!";
    } else {
        echo "Error al registrar el paciente: " . $stmt->error;
    }

    // 7. Cerrar la sentencia y la conexión
    $stmt->close();
    $conexion->close();

} else {
    // Si alguien intenta abrir este archivo directamente desde el navegador
    echo "Acceso no permitido.";
}
?>1