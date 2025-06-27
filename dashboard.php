<?php
// 1. CONEXIÓN Y CONSULTAS A LA BASE DE DATOS
// ===========================================
require_once 'conexion.php'; // o '../conexion.php' si este archivo está en la carpeta 'atencion/'

// --- Tarjetas de Estadísticas (KPIs) ---

// KPI 1: Atenciones para el día de hoy
$sql_citas_hoy = "SELECT COUNT(ID_Atencion) AS total FROM Atencion WHERE DATE(Fecha) = CURDATE()";
$resultado = $conexion->query($sql_citas_hoy);
$citas_hoy = ($resultado->fetch_assoc()['total']) ?? 0;

// KPI 2: Total de pacientes registrados
$sql_total_pacientes = "SELECT COUNT(RUT) AS total FROM Paciente";
$resultado = $conexion->query($sql_total_pacientes);
$total_pacientes = ($resultado->fetch_assoc()['total']) ?? 0;

// KPI 3: Total de atenciones en el mes actual
$sql_atenciones_mes = "SELECT COUNT(ID_Atencion) AS total FROM Atencion WHERE MONTH(Fecha) = MONTH(CURDATE()) AND YEAR(Fecha) = YEAR(CURDATE())";
$resultado = $conexion->query($sql_atenciones_mes);
$atenciones_mes = ($resultado->fetch_assoc()['total']) ?? 0;

// KPI 4: Ingresos totales del mes actual
$sql_ingresos_mes = "
    SELECT SUM(t.Valor) AS total
    FROM Atencion AS a
    INNER JOIN Tratamiento AS t ON a.ID_Tratamiento = t.ID_Tratamiento
    WHERE MONTH(a.Fecha) = MONTH(CURDATE()) AND YEAR(a.Fecha) = YEAR(CURDATE())
";
$resultado = $conexion->query($sql_ingresos_mes);
$ingresos_mes = ($resultado->fetch_assoc()['total']) ?? 0;

// --- Lista de Próximas Atenciones (3 más cercanas) ---
$sql_proximas_citas = "
    SELECT
        a.Fecha,
        p.Nombre AS NombrePaciente,
        t.Nombre AS NombreTratamiento
    FROM
        Atencion AS a
    INNER JOIN Paciente AS p ON a.RUT_Paciente = p.RUT
    INNER JOIN Tratamiento AS t ON a.ID_Tratamiento = t.ID_Tratamiento
    WHERE a.Fecha >= CURDATE()
    ORDER BY a.Fecha ASC
    LIMIT 3
";
$proximas_citas = $conexion->query($sql_proximas_citas);


// --- Lista de Tratamientos (Servicios) ---
$sql_tratamientos = "SELECT Nombre, Valor FROM Tratamiento ORDER BY Valor DESC LIMIT 3";
$lista_tratamientos = $conexion->query($sql_tratamientos);

// --- Datos para el Gráfico (Atenciones por Tratamiento) ---
$sql_chart = "
    SELECT
        t.Nombre,
        COUNT(a.ID_Atencion) AS cantidad
    FROM Atencion AS a
    INNER JOIN Tratamiento AS t ON a.ID_Tratamiento = t.ID_Tratamiento
    GROUP BY t.Nombre
    ORDER BY cantidad DESC
";
$resultado_chart = $conexion->query($sql_chart);
$chart_labels = [];
$chart_data = [];
if ($resultado_chart) {
    while ($fila = $resultado_chart->fetch_assoc()) {
        $chart_labels[] = $fila['Nombre'];
        $chart_data[] = $fila['cantidad'];
    }
}
$chart_labels_json = json_encode($chart_labels);
$chart_data_json = json_encode($chart_data);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalSonrisa - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-tooth"></i>
                    <h2>DentalSonrisa</h2>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active">
                        <a href="#" class="nav-link"> <!-- Enlace al dashboard actual -->
                            <i class="fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="atencion/listar_atenciones.php" class="nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Atenciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pacientes/listar_pacientes.php" class="nav-link"> <!-- Asumiendo una ruta futura -->
                            <i class="fas fa-users"></i>
                            <span>Pacientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="tratamientos/listar_tratamientos.php" class="nav-link"> <!-- Asumiendo una ruta futura -->
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="user-profile">
                        <i class="fas fa-user-circle"></i>
                        <span>Usuario</span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $citas_hoy; ?></h3>
                            <p>Atenciones Hoy</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $total_pacientes; ?></h3>
                            <p>Total Pacientes</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-notes-medical"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $atenciones_mes; ?></h3>
                            <p>Atenciones Este Mes</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h3>$<?php echo number_format($ingresos_mes, 0, ',', '.'); ?></h3>
                            <p>Ingresos del Mes</p>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables Row -->
                <div class="content-row">
                    <!-- Appointments Chart -->
                    <div class="card chart-card">
                        <div class="card-header">
                            <h3>Atenciones por Tratamiento</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="servicesChart"></canvas>
                        </div>
                    </div>

                    <!-- Recent Appointments -->
                    <div class="card appointments-card">
                        <div class="card-header">
                            <h3>Próximas Atenciones</h3>
                            <a href="atencion/formulario_atencion.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Nueva Atención
                            </a>
                        </div>
                        <div class="appointments-list">
                            <?php if ($proximas_citas && $proximas_citas->num_rows > 0): ?>
                                <?php while ($cita = $proximas_citas->fetch_assoc()): ?>
                                    <div class="appointment-item">
                                        <div class="appointment-time">
                                            <span class="date"><?php echo date("d-m-Y", strtotime($cita['Fecha'])); ?></span>
                                        </div>
                                        <div class="appointment-info">
                                            <h4><?php echo htmlspecialchars($cita['NombrePaciente']); ?></h4>
                                            <p><?php echo htmlspecialchars($cita['NombreTratamiento']); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="appointment-item">
                                    <p>No hay atenciones programadas.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Services Overview -->
                <div class="card services-overview">
                    <div class="card-header">
                        <h3>Resumen de Tratamientos</h3>
                    </div>
                    <div class="services-grid">
                        <?php if ($lista_tratamientos && $lista_tratamientos->num_rows > 0): ?>
                            <?php while ($tratamiento = $lista_tratamientos->fetch_assoc()): ?>
                                <div class="service-item">
                                    <div class="service-icon"><i class="fas fa-tooth"></i></div>
                                    <div class="service-info">
                                        <h4><?php echo htmlspecialchars($tratamiento['Nombre']); ?></h4>
                                        <span class="price">$<?php echo number_format($tratamiento['Valor'], 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="padding: 20px;">No hay tratamientos registrados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Script para inicializar el gráfico de Chart.js
        const ctx = document.getElementById('servicesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut', // Tipo de gráfico: dona
                data: {
                    labels: <?php echo $chart_labels_json; ?>,
                    datasets: [{
                        label: 'Cantidad de Atenciones',
                        data: <?php echo $chart_data_json; ?>,
                        backgroundColor: [ // Colores para cada sección del gráfico
                            '#667eea',
                            '#f093fb',
                            '#4facfe',
                            '#43e97b',
                            '#f5576c',
                            '#ffc107'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Distribución de Atenciones'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
<?php
// Cerrar la conexión
$conexion->close();
?>