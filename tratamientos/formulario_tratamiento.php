<?php
require_once '../conexion.php';

$sql_especialistas = "SELECT ID_Especialista, Nombre FROM Especialista ORDER BY Nombre ASC";
$resultado_especialistas = $conexion->query($sql_especialistas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Tratamiento</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Registrar Nuevo Tratamiento</h1>

    <form action="guardar_tratamiento.php" method="POST">
        
        <label for="nombre">Nombre del Tratamiento:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="valor">Valor:</label>
        <input type="number" id="valor" name="valor" step="0.01" required>

        <label for="id_especialista">Especialista que lo realiza:</label>
        <select id="id_especialista" name="id_especialista" required>
            <option value="">-- Seleccione un especialista --</option>
            <?php
            if ($resultado_especialistas->num_rows > 0) {
                while($fila = $resultado_especialistas->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($fila['ID_Especialista']) . "'>" . htmlspecialchars($fila['Nombre']) . "</option>";
                }
            }
            ?>
        </select>
        
        <input type="submit" value="Guardar Tratamiento">
    </form>
</body>
</html>
<?php $conexion->close(); ?>