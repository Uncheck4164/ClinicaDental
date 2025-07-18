<?php
require_once '../conexion.php';

$sql = "
    SELECT
        a.ID_Atencion,
        a.Fecha,
        p.Nombre AS NombrePaciente,
        t.Nombre AS NombreTratamiento,
        t.Valor,
        e.Nombre AS NombreEspecialista,
        fp.Descripcion AS FormaDePago
    FROM
        Atencion AS a
    INNER JOIN
        Paciente AS p ON a.RUT_Paciente = p.RUT
    INNER JOIN
        Tratamiento AS t ON a.ID_Tratamiento = t.ID_Tratamiento
    LEFT JOIN
        Especialista AS e ON t.ID_Especialista = e.ID_Especialista
    INNER JOIN
        FormaPago AS fp ON a.ID_FormaPago = fp.ID_FormaPago
    ORDER BY
        a.Fecha DESC, p.Nombre ASC
";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Atenciones</title>
    <link rel="stylesheet" href="../general.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php
if (isset($_GET['status'])) {
    $msg = '';
    if ($_GET['status'] === 'success') {
        $msg = 'editado con éxito.';
    } elseif ($_GET['status'] === 'deleted') {
        $msg = 'eliminado con éxito.';
    }
    if ($msg) {
        echo '<div class="custom-notification" id="notif-success">'
            . '<span>' . $msg . '</span>'
            . '<button class="close-btn" onclick="document.getElementById(\'notif-success\').style.display=\'none\';">&times;</button>'
            . '</div>';
    }
}
?>
<aside class="sidebar">
                <div class="sidebar-header">
                    <div class="logo">
                        <i class="fas fa-tooth"></i>
                        <h2>DentalSonrisa</h2>
                    </div>
                </div>
                
                <nav class="sidebar-nav">
                    <ul>
                        <li class="nav-item">
                            <a href="../dashboard.php" class="nav-link">
                                <i class="fas fa-chart-pie"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a href="../atencion/listar_atenciones.php" class="nav-link">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Atenciones</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../pacientes/listar_pacientes.php" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span>Pacientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../tratamientos/listar_tratamientos.php" class="nav-link">
                                <i class="fas fa-tooth"></i>
                                <span>Tratamientos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../pago/listar_pago.php" class="nav-link">
                                <i class="fas fa-credit-card"></i>
                                <span>Método de Pago</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../especialistas/listar_especialistas.php" class="nav-link">
                                <i class="fas fa-user-md"></i>
                                <span>Especialista</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
    <div class="container">
        <h1>Listado de Atenciones</h1>
        <p><a href="formulario_atencion.php" class="btn-crear">Registrar Nueva Atención</a></p>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Especialista</th>
                    <th>Forma de Pago</th>
                    <th class="text-right">Monto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $fecha_formateada = date("d-m-Y", strtotime($fila['Fecha']));
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fecha_formateada) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['NombrePaciente']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['NombreTratamiento']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['NombreEspecialista'] ?? 'No asignado') . "</td>";
                        echo "<td>" . htmlspecialchars($fila['FormaDePago']) . "</td>";
                        echo "<td class='text-right'>$" . number_format($fila['Valor'], 0, ',', '.') . "</td>";
                        echo "<td>";
                        echo "<a href='editar_atencion.php?id=" . urlencode($fila['ID_Atencion']) . "' class='btn-editar'>Editar</a>";
                        echo "<a href='eliminar_atencion.php?id=" . urlencode($fila['ID_Atencion']) . "' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta atención?\");'>Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay atenciones registradas.</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>