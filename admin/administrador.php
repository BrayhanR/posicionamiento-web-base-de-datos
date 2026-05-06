<?php // CONEXIÓN Y LÓGICA DE PROCESAMIENTO
require_once '../main/PHP/conexion.php';
// INICIAR SESIÓN - Obligatorio para poder guardar datos que viajen entre páginas
session_start();

// Validar específicamente la llave del admin
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../auth/ingreso-administrador.php"); 
    exit();
}
$id_logueado = $_SESSION['id_admin']; // Ahora usa la llave del admin

// --- LÓGICA PARA ELIMINAR USUARIO ---
if (isset($_POST['eliminar'])) {
    $id = $_POST['id_usuario'];
    $sql_delete = "DELETE FROM usuario WHERE id_usuario = '$id'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        echo "<script>alert('Usuario eliminado'); window.location='administrador.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar');</script>";
    }
}

// --- LÓGICA PARA ACTUALIZAR USUARIO EXISTENTE ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_usuario'];
    $nom = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $tel = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $dir = mysqli_real_escape_string($conexion, $_POST['direccion']);

    $sql_update = "UPDATE usuario SET nombre='$nom', telefono='$tel', direccion='$dir' WHERE id_usuario='$id'";
    
    if (mysqli_query($conexion, $sql_update)) {
        echo "<script>alert('Usuario actualizado correctamente'); window.location='administrador.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar');</script>";
    }
}

// --- LÓGICA PARA AGREGAR USUARIO NUEVO ---
if (isset($_POST['agregar'])) {    
    $ced = mysqli_real_escape_string($conexion, $_POST['cedula']); 
    $nom = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $cor = mysqli_real_escape_string($conexion, $_POST['correo']);
    $tel = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $dir = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $pass = password_hash("12345", PASSWORD_DEFAULT); 
    
    $sql_insert = "INSERT INTO usuario (cedula, nombre, correo, telefono, direccion, contrasena, rol) 
                   VALUES ('$ced', '$nom', '$cor', '$tel', '$dir', '$pass', 'pasajero')";
    
    if (mysqli_query($conexion, $sql_insert)) {
        echo "<script>alert('Nuevo usuario registrado con éxito'); window.location='administrador.php';</script>";
    } else {
        $error_db = mysqli_error($conexion);
        echo "<script>alert('Error de base de datos: " . addslashes($error_db) . "');</script>";
    }
}

// CONSULTA PARA LA TABLA 
$consulta = "SELECT id_usuario, cedula, nombre, correo, telefono, direccion, rol FROM usuario WHERE rol != 'admin' ORDER BY id_usuario DESC";
$resultado = mysqli_query($conexion, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/admin styles/administrador.css">
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
                <p class="banner-subtitulo">Gestión de Pasajeros</p>
                <h1>Usuarios</h1>
            </div>
        </div>

        <div id="menu">
            <a href="administrador.php" class="active">Usuarios</a>
            <a href="transportadoras-admin.php">Transportadoras</a>
            <a href="rutas-admin.php">Rutas y Servicios</a>
            <a href="reservas-admin.php">Reservas Globales</a>  
            <a href="cerrar-sesion-admin.php" style="color: #ff4d4d;">Cerrar Sesión</a>                     
        </div>

        <div class="paragraphs">
            <div class="column">
                <div class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Lista de Usuarios Registrados</h2>                        
                    </div>
                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                <tr id="nuevo">
                                    <form method="POST">
                                        <td>N/A</td>
                                        <td><input type="number" name="cedula" placeholder="Cédula" required></td>
                                        <td><input type="text" name="nombre" placeholder="Nombre" required></td>
                                        <td><input type="email" name="correo" placeholder="Correo" required></td>
                                        <td><input type="text" name="telefono" placeholder="Teléfono"></td>
                                        <td><input type="text" name="direccion" placeholder="Dirección"></td>
                                        <td><span class="badge inactivo-badge">Nuevo</span></td>
                                        <td><button type="submit" name="agregar" class="btn-primario">+ Registrar</button></td>
                                    </form>
                                </tr>

                                <?php
                                while ($fila = mysqli_fetch_array($resultado)) { 
                                    $tel_vacio = ($fila['telefono'] == '0' || empty($fila['telefono']));
                                    $dir_vacia = (empty($fila['direccion']));
                                    $clase_badge = ($tel_vacio || $dir_vacia) ? "inactivo-badge" : "activo-badge";
                                    $estado_texto = ($tel_vacio || $dir_vacia) ? "Incompleto" : "Verificado";
                                ?>
                                    <tr>
                                        <form method="POST" id="form-<?php echo $fila['id_usuario']; ?>">
                                            <td>
                                                #<?php echo $fila['id_usuario']; ?>
                                                <input type="hidden" name="id_usuario" value="<?php echo $fila['id_usuario']; ?>">
                                            </td>
                                            <td><?php echo $fila['cedula']; ?></td>
                                            <td><input type="text" name="nombre" value="<?php echo $fila['nombre']; ?>"></td>
                                            <td><?php echo $fila['correo']; ?></td>
                                            <td><input type="text" name="telefono" value="<?php echo $fila['telefono']; ?>"></td>
                                            <td><input type="text" name="direccion" value="<?php echo $fila['direccion']; ?>"></td>
                                            <td><span class="badge <?php echo $clase_badge; ?>"><?php echo $estado_texto; ?></span></td>
                                            <td style="display: flex; gap: 5px;">
                                                <button type="submit" name="actualizar" class="btn-primario" onclick="return confirmarCambio()">Guardar</button>                                                
                                                <button type="submit" name="eliminar" class="btn-secundario" onclick="return confirmarEliminar()">Borrar</button>
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
        <footer class="footer">
            <p>Curso: Desarrollo de aplicaciones para web - (202047916A_2201)</p>
        </footer> 
    </div>

    <script>
        function confirmarCambio() {
            return confirm("¿Deseas aplicar los cambios?");
        }

        function confirmarEliminar() {
            return confirm("¿ESTÁS SEGURO? Esta acción eliminará al usuario permanentemente y no se puede deshacer.");
        }
    </script>
</body>
</html>