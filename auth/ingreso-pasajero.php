<?php // CONEXIÓN Y LÓGICA DE PROCESAMIENTO
require_once '../main/PHP/conexion.php';
// INICIAR SESIÓN - Obligatorio para poder guardar datos que viajen entre páginas 
session_start(); 

// Captura de datos del formulario
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$pass1   = mysqli_real_escape_string($conexion, $_POST['contrasena']);

// IMPORTANTE: Definimos qué rol esperamos en este archivo de ingreso, en este caso el rol debe ser 'pasajero'
$rol_esperado = "pasajero";

// Consulta para buscar al usuario
$consulta = "SELECT * FROM usuario WHERE correo = '$correo' AND contrasena = '$pass1' AND rol = '$rol_esperado'";
$resultado = mysqli_query($conexion, $consulta);

// Validación
// Si no hay resultados, puede ser por dos razones:
    // 1. Datos mal escritos.
    // 2. Los datos están bien, pero el usuario es ADMINISTRADOR e intenta entrar por PASAJERO.
if (mysqli_num_rows($resultado) > 0) { 
    $datos_usuario = mysqli_fetch_array($resultado);

    // VARIABLE DE SESIÓN (La "llave" para entrar a las otras páginas)
    $_SESSION['id_usuario'] = $datos_usuario['id_usuario'];
    $_SESSION['rol'] = 'pasajero';

    $nombre = $datos_usuario['nombre'];
    
    echo "<script> 
        alert('¡Bienvenido, $nombre! Has ingresado correctamente.');
        window.location.href = '../cliente/cliente.php'; 
    </script>";
} else {    
    echo "<script>
        alert('Error: Credenciales incorrectas o no tienes permisos de pasajero.');
        window.history.back();
    </script>";
}

mysqli_close($conexion);
?>