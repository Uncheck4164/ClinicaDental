<?php
 // phpcs:ignore PEAR.Commenting.FileComment.Missing
 
$server = "localhost";
$user = "root";
$pass = "";
$db = "DentalSonrisa";

$conexion = new mysqli($server, $user, $pass, $db);

// por si sucede algun error
if ($conexion->connect_errno) {
    die("conexion fallida" . $conexion->connect_errno);
} else {
}
?>