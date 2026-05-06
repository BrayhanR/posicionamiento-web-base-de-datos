<?php // CONEXIÓN Y LÓGICA DE PROCESAMIENTO
require_once '../main/PHP/conexion.php';
// INICIAR SESIÓN - Obligatorio para poder guardar datos que viajen entre páginas 
session_start();

// Validar que exista el id de usuario Y que el rol sea pasajero
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'pasajero') {
    header("Location: ../auth/ingreso-pasajero.html?error=no_auth"); 
    exit();
}
$id_logueado = $_SESSION['id_usuario'];

// --- LÓGICA PARA ACTUALIZAR (SOLO TELÉFONO Y DIRECCIÓN) ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_usuario'];
    
    if ($id == $id_logueado) {
        $tel = mysqli_real_escape_string($conexion, $_POST['telefono']);
        $dir = mysqli_real_escape_string($conexion, $_POST['direccion']);

        $sql_update = "UPDATE usuario SET telefono='$tel', direccion='$dir' WHERE id_usuario='$id'";
        
        if (mysqli_query($conexion, $sql_update)) {
            echo "<script>alert('Tu información ha sido actualizada'); window.location='cliente.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar');</script>";
        }
    }
}

// CONSULTA FILTRADA: Solo el usuario que inició sesión
$consulta = "SELECT id_usuario, cedula, nombre, correo, telefono, direccion, rol FROM usuario WHERE id_usuario = '$id_logueado'";
$resultado = mysqli_query($conexion, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasajero</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/cliente styles/cliente.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="students">
                <h4>Estudiantes:</h4>
                <h4>Emmanuel Santiago Fernández López</h4>
                <h4>Yuliana Moreno Pérez</h4>
                <h4>Albert Daniel Ramírez García</h4>
                <h4>Brayhan Sty Rodriguez Rueda</h4>
                <h4>María Alejandra Urrea Peña</h4>            
            </div>
            <div id="UNADlogo">
                <img src="../assets/images/Logo_unad_color.png">
            </div>            
        </div>

        <div class="banner">
            <div class="banner-texto">
                <p class="banner-subtitulo">Gestión de Registro</p>
                <h1>Pasajero</h1>
            </div>
        </div>

        <div id="menu">
            <a href="cliente.php" class="active">Mi Perfil</a>
            <a href="reservas-cliente.php">Nueva Reserva</a>
            <a href="mis-reservas.php">Mis Reservas</a>
            <a href="cerrar-sesion-usuario.php" style="color: #ff4d4d;">Cerrar Sesión</a>                      
        </div>

        <div class="paragraphs">
            <div class="column">
                <div class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Mi información Personal</h2>                        
                    </div>
                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>Cédula</th> 
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Estado información</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                <?php
                                if ($fila = mysqli_fetch_array($resultado)) { 
                                    $tel_vacio = ($fila['telefono'] == '0' || empty($fila['telefono']));
                                    $dir_vacia = (empty($fila['direccion']));
                                    $clase_badge = ($tel_vacio || $dir_vacia) ? "inactivo-badge" : "activo-badge";
                                    $estado_texto = ($tel_vacio || $dir_vacia) ? "Incompleto" : "Verificado";
                                ?>
                                    <tr>
                                        <form method="POST">
                                            <input type="hidden" name="id_usuario" value="<?php echo $fila['id_usuario']; ?>">
                                            
                                            <td><?php echo $fila['cedula']; ?></td>
                                            <td><?php echo $fila['nombre']; ?></td>
                                            <td><?php echo $fila['correo']; ?></td>
                                            
                                            <td><input type="text" name="telefono" value="<?php echo $fila['telefono']; ?>" required></td>
                                            <td><input type="text" name="direccion" value="<?php echo $fila['direccion']; ?>" required></td>
                                            
                                            <td><span class="badge <?php echo $clase_badge; ?>"><?php echo $estado_texto; ?></span></td>
                                            
                                            <td>
                                                <button type="submit" name="actualizar" class="btn-primario" onclick="return confirmarCambio()">Guardar Cambios</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div>
        </div>
        <div class="options">                    
            <p>¿Quieres cambiar tu cédula, nombre, o correo? - <a href="https://www.terminaldetransporte.gov.co/atencion-y-servicios-a-la-ciudadania/">Contacta con la terminal de transporte</a></p>
        </div>
        <footer class="footer">
            <p>Curso: Desarrollo de aplicaciones para web - (202047916A_2201)</p>
        </footer> 
    </div>

    <script>
        function confirmarCambio() {
            return confirm("¿Deseas actualizar tu información personal?");
        }
    </script>
</body>
</html>