<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
require_once '../conexion.php';
$sql = "SELECT Rut, Nombre, Email FROM Paciente ORDER BY Nombre ASC";
$resultado = $conexion->query($sql);
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
                        <li class="nav-item ">
                            <a href="../atencion/listar_atenciones.php" class="nav-link">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Atenciones</span>
                            </a>
                        </li>
                        <li class="nav-item active">
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

    <h1>Listado de Pacientes</h1>
    <p><a href="formulario.html">Registrar un nuevo paciente</a></p>

    <table>
        <thead>
            <tr>
                <th>RUT</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['Rut']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Email']) . "</td>";
                    echo "<td>";
                    echo "<a href='editar_paciente.php?rut=" . urlencode($fila['Rut']) . "' class='btn-editar'>Editar</a>";
                    echo "<a href='eliminar_paciente.php?rut=" . urlencode($fila['Rut']) . "' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este paciente?\");'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay pacientes registrados.</td></tr>";
            }
            $conexion->close();
            ?>
        </tbody>
    </table>
        </div>
<script>
// cerrar automáticamente después de unos segundos
setTimeout(function() {
  var notif = document.getElementById('notif-success');
  if (notif) notif.style.display = 'none';
}, 4000);
</script>
</body>
</html>