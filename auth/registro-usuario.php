<?php // CONEXIÓN Y LÓGICA DE PROCESAMIENTO
require_once '../main/PHP/conexion.php';

// Insertar los datos
// mysqli_real_escape_string: Esto limpia los datos antes de enviarlos a la base de datos
$cedula   = mysqli_real_escape_string($conexion, $_POST['cedula']);
$nombre   = mysqli_real_escape_string($conexion, $_POST['first-name']);
$apellido = mysqli_real_escape_string($conexion, $_POST['last-name']);
$correo   = mysqli_real_escape_string($conexion, $_POST['email']);
$dir      = mysqli_real_escape_string($conexion, $_POST['address']);
$tel      = mysqli_real_escape_string($conexion, $_POST['telefono'] ?? '0'); 
$rol      = mysqli_real_escape_string($conexion, $_POST['rol']);; // Para indicar el tipo de rol al momento del registro
$pass1    = mysqli_real_escape_string($conexion, $_POST['password1']);
$pass2    = mysqli_real_escape_string($conexion, $_POST['password2']);

// Validación de contraseñas
if ($pass1 !== $pass2) {    
    echo "<script>
        alert('Error: Las contraseñas no coinciden. Por favor, intenta de nuevo.');
        window.history.back();
    </script>";
    exit; // Para que no se ejecute el INSERT
}

// Inserción en la base de datos
$sql = "INSERT INTO usuario (cedula, nombre, apellido, correo, direccion, telefono, rol, contrasena) 
        VALUES ('$cedula', '$nombre', '$apellido', '$correo', '$dir', '$tel', '$rol', '$pass1')";

if (mysqli_query($conexion, $sql)) { // Usamos JavaScript para mostrar una alerta y luego redirigir
    session_start(); // Logica de inicio de sesión    
    // Guardamos en la sesión los datos que acabamos de registrar
    // Para el ID, usamos mysqli_insert_id para obtener el ID generado automáticamente
    $_SESSION['id_usuario'] = mysqli_insert_id($conexion); 
    $_SESSION['rol'] = $rol;

    echo "<script> 
        alert('¡Registro completado exitosamente como: $rol!');
        window.location.href = '../cliente/cliente.php';
    </script>";
} else {
    if (mysqli_errno($conexion) == 1062) {
        echo "<script>
            alert('Error: La cédula o el correo ya están registrados.');
            window.history.back();
        </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);
?>