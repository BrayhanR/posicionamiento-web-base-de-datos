<?php
// Credenciales de la base de datos 
$servidor = "localhost";
$usuario = "root";
$password = "Genesis2026#"; 
$base_datos = "mi_proyecto";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>