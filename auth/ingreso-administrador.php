<?php // CONEXIÓN Y LÓGICA DE PROCESAMIENTO
require_once '../main/PHP/conexion.php';
// INICIAR SESIÓN - Obligatorio para poder guardar datos que viajen entre páginas 
session_start(); 

// Captura de datos del formulario
$id_login = mysqli_real_escape_string($conexion, $_POST['usuario']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$pass1   = mysqli_real_escape_string($conexion, $_POST['contrasena']);

// IMPORTANTE: Definimos qué rol esperamos en este archivo de ingreso, en este caso el rol debe ser "admin"
$rol_esperado = "admin";

// Consulta para buscar al usuario
$consulta = "SELECT * FROM usuario WHERE id_usuario = '$id_login' AND correo = '$correo' AND contrasena = '$pass1' AND rol = '$rol_esperado'";
$resultado = mysqli_query($conexion, $consulta);

// Validación
// Si no hay resultados, puede ser por dos razones:
    // 1. Datos mal escritos.
    // 2. Los datos están bien, pero el usuario es PASAJERO e intenta entrar por ADMINISTRADOR.
if (mysqli_num_rows($resultado) > 0) {
    $datos_usuario = mysqli_fetch_array($resultado);

    // VARIABLE DE SESIÓN (La "llave" para entrar a las otras páginas)
    $_SESSION['id_admin'] = $datos_usuario['id_usuario'];
    $_SESSION['rol'] = 'admin';

    $nombre = $datos_usuario['nombre'];
    
    echo "<script>
        alert('¡Bienvenido, $nombre! Has ingresado correctamente.');
        window.location.href = '../admin/administrador.php'; 
    </script>";
} else {    
    echo "<script>
        alert('Error: Credenciales incorrectas o no tienes permisos de administrador.');
        window.history.back();
    </script>";
}
mysqli_close($conexion);
?>