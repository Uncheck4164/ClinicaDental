<?php
require_once '../conexion.php';

// 2. Validar que se recibió un ID
if (!isset($_GET['id_tratamiento'])) {
    header('Location: listar_tratamientos.php');
    exit();
}

$id_tratamiento_a_editar = $_GET['id_tratamiento'];

$sql_tratamiento = "SELECT ID_Tratamiento, Nombre, Valor, ID_Especialista FROM Tratamiento WHERE ID_Tratamiento = ?";
$stmt = $conexion->prepare($sql_tratamiento);
$stmt->bind_param("i", $id_tratamiento_a_editar);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Tratamiento no encontrado.";
    exit();
}
$tratamiento = $resultado->fetch_assoc();
$stmt->close();

// 4. CONSULTA 2: Obtener TODOS los especialistas para el menú desplegable
$sql_especialistas = "SELECT ID_Especialista, Nombre FROM Especialista ORDER BY Nombre ASC";
$resultado_especialistas = $conexion->query($sql_especialistas);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tratamiento</title>
    <!-- Reutiliza los mismos estilos que ya tienes -->
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h1>Editar Tratamiento</h1>

    <form action="actualizar_tratamiento.php" method="POST">
        
        <!-- Campo oculto para enviar el ID del tratamiento que estamos editando -->
        <input type="hidden" name="id_tratamiento" value="<?php echo htmlspecialchars($tratamiento['ID_Tratamiento']); ?>">

        <label for="nombre">Nombre del Tratamiento:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tratamiento['Nombre']); ?>" required>

        <label for="valor">Valor:</label>
        <input type="number" id="valor" name="valor" step="0.01" value="<?php echo htmlspecialchars($tratamiento['Valor']); ?>" required>

        <label for="id_especialista">Especialista que lo realiza:</label>
        <select id="id_especialista" name="id_especialista" required>
            <option value="">-- Seleccione un especialista --</option>
            <?php
            if ($resultado_especialistas->num_rows > 0) {
                while($especialista = $resultado_especialistas->fetch_assoc()) {
                    // Lógica para pre-seleccionar el especialista actual del tratamiento
                    $selected = ($especialista['ID_Especialista'] == $tratamiento['ID_Especialista']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($especialista['ID_Especialista']) . "' $selected>" . htmlspecialchars($especialista['Nombre']) . "</option>";
                }
            }
            ?>
        </select>
        
        <input type="submit" value="Guardar Cambios">
    </form>

</body>
</html>
<?php $conexion->close(); ?>