<?php
require_once '../main/PHP/conexion.php';

// --- LÓGICA PARA ELIMINAR EMPRESA ---
if (isset($_POST['eliminar'])) {
    $id = $_POST['id_transportador'];
    // Nota: Si hay servicios asociados, esto podría dar error por integridad referencial
    $sql_delete = "DELETE FROM transportador WHERE id_transportador = '$id'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        echo "<script>alert('Empresa aliada eliminada'); window.location='transportadoras-admin.php';</script>";
    } else {
        echo "<script>alert('Error: No se puede eliminar una empresa que tiene servicios activos');</script>";
    }
}

// --- LÓGICA PARA ACTUALIZAR NOMBRE DE EMPRESA ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_transportador'];
    $nom = mysqli_real_escape_string($conexion, $_POST['nombre_empresa']);

    $sql_update = "UPDATE transportador SET nombre_empresa='$nom' WHERE id_transportador='$id'";
    
    if (mysqli_query($conexion, $sql_update)) {
        echo "<script>alert('Información de empresa actualizada'); window.location='transportadoras-admin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar');</script>";
    }
}

// --- LÓGICA PARA AGREGAR NUEVA EMPRESA ---
if (isset($_POST['agregar'])) {
    $nom = mysqli_real_escape_string($conexion, $_POST['nombre_empresa']);

    $sql_insert = "INSERT INTO transportador (nombre_empresa) VALUES ('$nom')";
    
    if (mysqli_query($conexion, $sql_insert)) {
        echo "<script>alert('Nueva empresa aliada registrada'); window.location='transportadoras-admin.php';</script>";
    } else {
        echo "<script>alert('Error al registrar la empresa');</script>";
    }
}

// CONSULTA PARA LA LISTA DE ALIADOS
$consulta = "SELECT id_transportador, nombre_empresa FROM transportador ORDER BY id_transportador DESC";
$resultado = mysqli_query($conexion, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Transportadoras</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/admin styles/transportadoras-admin.css">
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
                <p class="banner-subtitulo">Gestión de Empresas</p>
                <h1>Transportadoras</h1>
            </div>
        </div>

        <div id="menu">
            <a href="administrador.php">Usuarios</a>
            <a href="transportadoras-admin.php" class="active">Transportadoras</a>
            <a href="rutas-admin.php">Rutas y Servicios</a>
            <a href="reservas-admin.php">Reservas Globales</a>  
            <a href="cerrar-sesion-admin.php" style="color: #ff4d4d;">Cerrar Sesión</a>                    
        </div>

        <div class="paragraphs">
            <div class="column">
                <div class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Lista de Aliados Actuales</h2>                        
                    </div>
                    
                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de la Empresa</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="nuevo">
                                    <form method="POST">
                                        <td>N/A</td>
                                        <td>
                                            <input type="text" name="nombre_empresa" placeholder="Nombre de la empresa" required>
                                        </td>
                                        <td><span class="badge inactivo-badge">Nueva</span></td>
                                        <td>
                                            <button type="submit" name="agregar" class="btn-primario">+ Registrar Empresa</button>
                                        </td>
                                    </form>
                                </tr>

                                <?php
                                while ($fila = mysqli_fetch_array($resultado)) { 
                                ?>
                                    <tr>
                                        <form method="POST">
                                            <td>
                                                #<?php echo $fila['id_transportador']; ?>
                                                <input type="hidden" name="id_transportador" value="<?php echo $fila['id_transportador']; ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="nombre_empresa" value="<?php echo $fila['nombre_empresa']; ?>" required>
                                            </td>
                                            <td><span class="badge activo-badge">Aliado Activo</span></td>
                                            <td style="display: flex; gap: 5px;">
                                                <button type="submit" name="actualizar" class="btn-primario" onclick="return confirmarAccion('¿Deseas actualizar el nombre?')">Guardar</button>
                                                
                                                <button type="submit" name="eliminar" class="btn-secundario" onclick="return confirmarAccion('¿Eliminar esta empresa aliada?')">Borrar</button>
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
        function confirmarAccion(mensaje) {
            return confirm(mensaje);
        }
    </script>
</body>
</html>