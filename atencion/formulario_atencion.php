<?php
require_once '../conexion.php';

$sql_pacientes = "SELECT Rut, Nombre FROM Paciente ORDER BY Nombre ASC";
$resultado_pacientes = $conexion->query($sql_pacientes);

$sql_tratamientos = "
    SELECT
        t.ID_Tratamiento,
        t.Nombre AS NombreTratamiento,
        t.Valor,
        e.Nombre AS NombreEspecialista
    FROM
        Tratamiento AS t
    INNER JOIN
        Especialista AS e ON t.ID_Especialista = e.ID_Especialista
    ORDER BY
        t.Nombre ASC, e.Nombre ASC
";
$resultado_tratamientos = $conexion->query($sql_tratamientos);

$sql_formaspago = "SELECT ID_FormaPago, Descripcion FROM FormaPago ORDER BY Descripcion ASC";
$resultado_formaspago = $conexion->query($sql_formaspago);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nueva Atención</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="date"], select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: #0056b3; }
        #display-monto h3 { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Registrar Nueva Atención</h1>

    <form action="guardar_atencion.php" method="POST">

        <label for="rut_paciente">Paciente:</label>
        <select id="rut_paciente" name="rut_paciente" required>
            <option value="">-- Seleccione un paciente --</option>
            <?php
            if ($resultado_pacientes->num_rows > 0) {
                while($fila = $resultado_pacientes->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($fila['Rut']) . "'>" . htmlspecialchars($fila['Nombre']) . "</option>";
                }
            }
            ?>
        </select>

        <label for="id_tratamiento">Tratamiento:</label>
        <select id="id_tratamiento" name="id_tratamiento" required>
            <option value="">-- Seleccione un tratamiento --</option>
            <?php
            if ($resultado_tratamientos && $resultado_tratamientos->num_rows > 0) {
                while($fila = $resultado_tratamientos->fetch_assoc()) {
                    $id_tratamiento = htmlspecialchars($fila['ID_Tratamiento']);
                    $valor_tratamiento = htmlspecialchars($fila['Valor']);
                    $texto_opcion = htmlspecialchars($fila['NombreTratamiento']) . " - (" . htmlspecialchars($fila['NombreEspecialista']) . ")";
                    
                    echo "<option value='{$id_tratamiento}' data-valor='{$valor_tratamiento}'>{$texto_opcion}</option>";
                }
            }
            ?>
        </select>

        <label for="id_formapago">Forma de Pago:</label>
        <select id="id_formapago" name="id_formapago" required>
            <option value="">-- Seleccione una forma de pago --</option>
            <?php
            if ($resultado_formaspago->num_rows > 0) {
                while($fila = $resultado_formaspago->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($fila['ID_FormaPago']) . "'>" . htmlspecialchars($fila['Descripcion']) . "</option>";
                }
            }
            ?>
        </select>

        <label for="fecha">Fecha de la Atención:</label>
        <input type="date" id="fecha" name="fecha" required>

        <div id="display-monto">
            <h3>Monto Total: <span id="monto-a-pagar">$0</span></h3>
        </div>

        <input type="submit" value="Guardar Atención">
    </form>
    
    <script>
        const selectTratamiento = document.getElementById('id_tratamiento');
        const spanMonto = document.getElementById('monto-a-pagar');

        const formatoMoneda = new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP',
            minimumFractionDigits: 0
        });

        selectTratamiento.addEventListener('change', function() {
            const opcionSeleccionada = this.options[this.selectedIndex];
            const valorTratamiento = opcionSeleccionada.dataset.valor;
            
            const montoNumerico = parseFloat(valorTratamiento);
            const montoFinal = !isNaN(montoNumerico) ? montoNumerico : 0;
            
            spanMonto.textContent = formatoMoneda.format(montoFinal);
        });
    </script>

</body>
</html>
<?php $conexion->close(); ?>