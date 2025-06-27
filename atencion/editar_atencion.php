<?php
require_once '../conexion.php';

// Verificar que se ha proporcionado un ID
if (!isset($_GET['id'])) {
    die("ID de atención no proporcionado.");
}

$id_atencion = (int)$_GET['id'];

// --- 1. Obtener los datos de la atención específica que se va a editar ---
$sql_atencion = "SELECT * FROM Atencion WHERE ID_Atencion = ?";
$stmt_atencion = $conexion->prepare($sql_atencion);
$stmt_atencion->bind_param("i", $id_atencion);
$stmt_atencion->execute();
$resultado_atencion = $stmt_atencion->get_result();
$atencion = $resultado_atencion->fetch_assoc();
$stmt_atencion->close();

// Si no se encuentra la atención, terminar el script
if (!$atencion) {
    die("Atención no encontrada.");
}

// --- 2. Obtener las listas para los menús desplegables (igual que en el formulario de creación) ---
$sql_pacientes = "SELECT Rut, Nombre FROM Paciente ORDER BY Nombre ASC";
$resultado_pacientes = $conexion->query($sql_pacientes);

$sql_tratamientos = "SELECT ID_Tratamiento, Nombre, Valor FROM Tratamiento ORDER BY Nombre ASC";
$resultado_tratamientos = $conexion->query($sql_tratamientos);

$sql_formaspago = "SELECT ID_FormaPago, Descripcion FROM FormaPago ORDER BY Descripcion ASC";
$resultado_formaspago = $conexion->query($sql_formaspago);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Atención</title>
    <!-- Usamos los mismos estilos del formulario de creación -->
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="date"], select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #ffc107; color: black; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: #e0a800; }
        #display-monto h3 { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Editar Atención (ID: <?php echo htmlspecialchars($id_atencion); ?>)</h1>

    <!-- El formulario envía los datos a un nuevo script: actualizar_atencion.php -->
    <form action="actualizar_atencion.php" method="POST">

        <!-- CAMPO OCULTO: Es VITAL para saber qué registro actualizar -->
        <input type="hidden" name="id_atencion" value="<?php echo htmlspecialchars($atencion['ID_Atencion']); ?>">

        <label for="rut_paciente">Paciente:</label>
        <select id="rut_paciente" name="rut_paciente" required>
            <?php while($fila = $resultado_pacientes->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($fila['Rut']); ?>" <?php if($fila['Rut'] == $atencion['RUT_Paciente']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($fila['Nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="id_tratamiento">Tratamiento:</label>
        <select id="id_tratamiento" name="id_tratamiento" required>
             <?php while($fila = $resultado_tratamientos->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($fila['ID_Tratamiento']); ?>" data-valor="<?php echo htmlspecialchars($fila['Valor']); ?>" <?php if($fila['ID_Tratamiento'] == $atencion['ID_Tratamiento']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($fila['Nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="id_formapago">Forma de Pago:</label>
        <select id="id_formapago" name="id_formapago" required>
            <?php while($fila = $resultado_formaspago->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($fila['ID_FormaPago']); ?>" <?php if($fila['ID_FormaPago'] == $atencion['ID_FormaPago']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($fila['Descripcion']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="fecha">Fecha de la Atención:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($atencion['Fecha']); ?>" required>

        <div id="display-monto">
            <h3>Monto Total: <span id="monto-a-pagar">$0</span></h3>
        </div>

        <input type="submit" value="Actualizar Atención">
    </form>
    
    <script>
        // Script para mostrar el monto del tratamiento (igual que en el formulario de creación)
        const selectTratamiento = document.getElementById('id_tratamiento');
        const spanMonto = document.getElementById('monto-a-pagar');

        const formatoMoneda = new Intl.NumberFormat('es-CL', {
            style: 'currency', currency: 'CLP', minimumFractionDigits: 0
        });

        function actualizarMonto() {
            const opcionSeleccionada = selectTratamiento.options[selectTratamiento.selectedIndex];
            const valorTratamiento = opcionSeleccionada.dataset.valor;
            const montoNumerico = parseFloat(valorTratamiento);
            spanMonto.textContent = formatoMoneda.format(!isNaN(montoNumerico) ? montoNumerico : 0);
        }

        selectTratamiento.addEventListener('change', actualizarMonto);
        
        // Ejecutar una vez al cargar la página para mostrar el monto del tratamiento ya seleccionado
        document.addEventListener('DOMContentLoaded', actualizarMonto);
    </script>

</body>
</html>
<?php $conexion->close(); ?>