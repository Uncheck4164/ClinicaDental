<?php
require_once '../conexion.php';

// 2. Verificar que se ha accedido mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $valor = $_POST['valor'];
    $id_especialista = $_POST['id_especialista'];

    $sql = "INSERT INTO Tratamiento (Nombre, Valor, ID_Especialista) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    // 5. Vincular las variables
    // "sdi" significa: string, double, integer
    $stmt->bind_param("sdi", $nombre, $valor, $id_especialista);

    if ($stmt->execute()) {
        header("Location: listar_tratamientos.php?status=success");
    } else {
        echo "Error al registrar el tratamiento: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Acceso no permitido.";
}
?>