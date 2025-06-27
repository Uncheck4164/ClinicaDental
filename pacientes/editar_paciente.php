<?php
require_once '../conexion.php';

if (!isset($_GET['rut'])) {
    header('Location: listar_pacientes.php');
    exit();
}

$rut_a_editar = $_GET['rut'];

// Obtener los datos del paciente a editar
$sql = "SELECT Rut, Nombre, Email FROM Paciente WHERE Rut = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $rut_a_editar);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Paciente no encontrado.";
    $stmt->close();
    $conexion->close();
    exit();
}

$paciente = $resultado->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #218838; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
    </style>
</head>
<body>

    <h1>Editar Paciente</h1>

    <form action="actualizar_paciente.php" method="POST">

        <label for="rut">RUT del Paciente (no se puede cambiar):</label>
        <input type="text" id="rut" name="rut" value="<?php echo htmlspecialchars($paciente['Rut']); ?>" readonly>

        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paciente['Nombre']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($paciente['Email']); ?>" required>
        
        <input type="submit" value="Guardar Cambios">
    </form>

</body>
</html>
<?php
$conexion->close();
?>