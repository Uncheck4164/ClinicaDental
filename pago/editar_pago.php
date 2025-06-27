<?php
// 1. Incluir la conexión
require_once '../conexion.php';

// 2. Comprobar que hemos recibido el ID por GET
if (!isset($_GET['ID_FormaPago'])) {
    header('Location: listar_pago.php');
    exit();
}

$ID_FormaPago_a_editar = $_GET['ID_FormaPago'];

// 3. Preparar la consulta para obtener los datos del paciente
$sql = "SELECT ID_FormaPago, Descripcion FROM FormaPago WHERE ID_FormaPago = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

// 4. Vincular el RUT y ejecutar
$stmt->bind_param("s", $ID_FormaPago_a_editar);
$stmt->execute();
$resultado = $stmt->get_result();

// 5. Comprobar si el paciente existe
if ($resultado->num_rows === 0) {
    echo "Metodo de Pago no encontrado.";
    $stmt->close();
    $conexion->close();
    exit();
}

// 6. Obtener los datos del metodo de pago
$pago = $resultado->fetch_assoc();

$stmt->close();
// No cerramos la conexión principal todavía, la usaremos más adelante si es necesario.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Metodo de Pago</title>
    <!-- Usamos los mismos estilos que en tu formulario de nuevo paciente -->
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

    <h1>Editar Metodo de Pago</h1>

        <!-- El formulario envía los datos a 'actualizar_especialista.php' usando POST -->
    <form action="actualizar_pago.php" method="POST">

        <label for="ID_FormaPago">ID del Metodo de Pago (no se puede cambiar):</label>
        <!-- El ID no se debe poder editar, así que lo ponemos como 'readonly' -->
        <input type="text" id="ID_FormaPago" name="ID_FormaPago" value="<?php echo htmlspecialchars($pago['ID_FormaPago']); ?>" readonly>

        <label for="Descripcion">Descripcion:</label>
        <input type="text" id="Descripcion" name="Descripcion" value="<?php echo htmlspecialchars($pago['Descripcion']); ?>" required>

        
        <input type="submit" value="Guardar Cambios">
    </form>

</body>
</html>
<?php
$conexion->close();
?>