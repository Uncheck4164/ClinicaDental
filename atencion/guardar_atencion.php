<?php
// phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Incluir el archivo de conexión a la base de datos
require_once '../conexion.php';

// 2. Verificar que se ha accedido a este script mediante el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Recibir y guardar los datos del formulario en variables
    $rut_paciente   = $_POST['rut_paciente'];
    $id_tratamiento = $_POST['id_tratamiento'];
    $id_formapago   = $_POST['id_formapago'];
    $fecha          = $_POST['fecha'];

    // --- IMPORTANTE: Se elimina toda la lógica para obtener el monto, ya que no se va a guardar. ---

    // 4. Preparar la consulta SQL para insertar la atención (Forma Segura)
    // CORRECCIÓN 1: Se elimina la columna 'Monto' de la consulta y el '?' correspondiente.
    $sql = "INSERT INTO Atencion (Rut_Paciente, ID_Tratamiento, ID_FormaPago, Fecha) VALUES (?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);

    // 5. Vincular las variables a los marcadores de posición (?)
    // CORRECCIÓN 2: Se ajustan los tipos ("siis") y las variables para que coincidan (4 en total).
    // s: string (Rut_Paciente)
    // i: integer (ID_Tratamiento)
    // i: integer (ID_FormaPago)
    // s: string (Fecha)
    $stmt->bind_param("siis", $rut_paciente, $id_tratamiento, $id_formapago, $fecha);

    // 6. Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        // Si el registro es exitoso, redirigimos al listado de atenciones.
        header("Location: listar_atenciones.php?status=success");
        exit(); // Es buena práctica usar exit() después de una redirección
    } else {
        // Si hay un error, lo mostramos.
        echo "Error al registrar la atención: " . $stmt->error;
    }

    // 7. Cerrar la sentencia de inserción
    $stmt->close();

} else {
    // Si alguien intenta acceder a este archivo directamente, se le niega el acceso.
    echo "Acceso no permitido.";
}

// 8. Cerrar la conexión a la base de datos
$conexion->close();
?>