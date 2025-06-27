<?php
 // phpcs:ignore PEAR.Commenting.FileComment.WrongStyle
// (El código PHP de arriba para conectar y consultar sigue siendo el mismo)
require_once '../conexion.php';
$sql = "SELECT id_especialista, nombre FROM Especialista ORDER BY nombre ASC";
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
                        <li class="nav-item">
                            <a href="../pacientes/listar_pacientes.php" class="nav-link"> <!-- Asumiendo una ruta futura -->
                                <i class="fas fa-users"></i>
                                <span>Pacientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../tratamientos/listar_tratamientos.php" class="nav-link"> <!-- Asumiendo una ruta futura -->
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
                        <li class="nav-item active">
                            <a href="../especialistas/listar_especialistas.php" class="nav-link">
                                <i class="fas fa-user-md"></i>
                                <span>Especialista</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
    <div class="container">

    <h1>Listado de Especialistas</h1>
    <p><a href="formulario_especialista.html">Registrar un nuevo especialista</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['id_especialista']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                    
                    // Celda con los botones de Editar y Eliminar
                    echo "<td>";
                    echo "<a href='editar_especialista.php?id_especialista=" . urlencode($fila['id_especialista']) . "' class='btn-editar'>Editar</a>";
                    echo "<a href='eliminar_especialista.php?id_especialista=" . urlencode($fila['id_especialista']) . "' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este especialista?\");'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay especialistas registrados.</td></tr>";
            }
            $conexion->close();
            ?>
        </tbody>
    </table>
        </div>
</body>
</html>