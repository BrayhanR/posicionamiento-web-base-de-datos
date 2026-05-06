<?php
// 1. Permitir que el servidor reciba datos desde el archivo JS
header("Content-Type: application/json");

// 2. Capturamos el "paquete" de datos que envió el fetch
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 3. Credenciales de la base de datos (aca cada uno puede usar las claves y usuarios de sus tablas locales)
$servidor = "localhost";
$usuario = "root";
$password = "Genesis2026#"; 
$base_datos = "mi_proyecto";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// 4. Insertar los datos
// mysqli_real_escape_string: Esto limpia los datos antes de enviarlos a la base de datos
$nombre = mysqli_real_escape_string($conexion, $data['nombre']);
$tel    = mysqli_real_escape_string($conexion, $data['telefono']);
$mail   = mysqli_real_escape_string($conexion, $data['correo']);
$msg    = mysqli_real_escape_string($conexion, $data['comentarios']);

$sql = "INSERT INTO contactos (nombre, telefono, email, mensaje) 
        VALUES ('$nombre', '$tel', '$mail', '$msg')";

if(mysqli_query($conexion, $sql)) {
    echo "Guardado correctamente en MySQL";
} else {
    echo "Error al guardar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>