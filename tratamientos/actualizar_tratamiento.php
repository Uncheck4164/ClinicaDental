<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Recoger los datos del formulario
    $id_tratamiento = $_POST['id_tratamiento'];
    $nombre = $_POST['nombre'];
    $valor = $_POST['valor'];
    $id_especialista = $_POST['id_especialista'];

    if (empty($id_tratamiento) || empty($nombre) || empty($valor) || empty($id_especialista)) {
        die("Todos los campos son requeridos.");
    }

    $sql = "UPDATE Tratamiento SET Nombre = ?, Valor = ?, ID_Especialista = ? WHERE ID_Tratamiento = ?";
    
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta de actualización: " . $conexion->error);
    }
    
    // 5. Vincular los nuevos datos y el ID a la consulta
    // 's' para nombre (string), 'd' para valor (double/decimal), 'i' para IDs (integer)
    // El orden debe coincidir con los '?' en la consulta SQL
    $stmt->bind_param("sdii", $nombre, $valor, $id_especialista, $id_tratamiento);
    
    if ($stmt->execute()) {
        header('Location: listar_tratamientos.php?status=updated');
        exit();
    } else {
        echo "Error al actualizar el tratamiento: " . $stmt->error;
    }
    
    $stmt->close();
    $conexion->close();

} else {
    header('Location: listar_tratamientos.php');
    exit();
}
?>