<?php
require_once '../main/PHP/conexion.php';

// --- LÓGICA PARA ELIMINAR RUTA ---
if (isset($_POST['eliminar'])) {
    $id = $_POST['id_servicio'];
    $sql_delete = "DELETE FROM servicio WHERE id_servicio = '$id'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        echo "<script>alert('Ruta eliminada'); window.location='rutas-admin.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar: La ruta podría tener reservas asociadas');</script>";
    }
}

// --- LÓGICA PARA ACTUALIZAR RUTA ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_servicio'];
    $fk_trans = $_POST['id_transportador'];
    $orig = mysqli_real_escape_string($conexion, $_POST['origen']);
    $dest = mysqli_real_escape_string($conexion, $_POST['destino']);
    $cost = $_POST['costo'];
    $ruta_nom = $orig . " - " . $dest; // Generar nombre de ruta automáticamente

    $sql_update = "UPDATE servicio SET id_transportador='$fk_trans', ruta='$ruta_nom', origen='$orig', destino='$dest', costo='$cost' WHERE id_servicio='$id'";
    
    if (mysqli_query($conexion, $sql_update)) {
        echo "<script>alert('Ruta actualizada'); window.location='rutas-admin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar');</script>";
    }
}

// --- LÓGICA PARA AGREGAR NUEVA RUTA ---
if (isset($_POST['agregar'])) {
    $fk_trans = $_POST['id_transportador'];
    $orig = mysqli_real_escape_string($conexion, $_POST['origen']);
    $dest = mysqli_real_escape_string($conexion, $_POST['destino']);
    $cost = $_POST['costo'];
    $ruta_nom = $orig . " - " . $dest;
    
    $sql_insert = "INSERT INTO servicio (id_transportador, ruta, origen, destino, costo) 
               VALUES ('$fk_trans', '$ruta_nom', '$orig', '$dest', '$cost')";
    
    if (mysqli_query($conexion, $sql_insert)) {
        echo "<script>alert('Nueva ruta registrada con éxito'); window.location='rutas-admin.php';</script>";
    } else {
        echo "<script>alert('Error al registrar la ruta');</script>";
    }
}

// CONSULTAS PARA LA VISTA
// Consulta de Rutas con JOIN para traer nombre de empresa
$sql_rutas = "SELECT s.*, t.nombre_empresa FROM servicio s 
              INNER JOIN transportador t ON s.id_transportador = t.id_transportador 
              ORDER BY s.id_servicio DESC";
$res_rutas = mysqli_query($conexion, $sql_rutas);

// CONSULTAS DE TRANSPORTADORAS PARA LOS SELECTS (menús desplegables)
$sql_trans = "SELECT id_transportador, nombre_empresa FROM transportador ORDER BY nombre_empresa ASC";
$res_trans_nuevo = mysqli_query($conexion, $sql_trans);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Rutas y Servicios</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/admin styles/rutas-admin.css">
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
                <p class="banner-subtitulo">Gestión de Servicios</p>
                <h1>Rutas y Servicios</h1>
            </div>
        </div>

        <div id="menu">
            <a href="administrador.php">Usuarios</a>
            <a href="transportadoras-admin.php">Transportadoras</a>
            <a href="rutas-admin.php" class="active">Rutas y Servicios</a>
            <a href="reservas-admin.php">Reservas Globales</a> 
            <a href="cerrar-sesion-admin.php" style="color: #ff4d4d;">Cerrar Sesión</a>                     
        </div>

        <div class="paragraphs">
            <div class="column">
                <div class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Catálogo de Rutas Disponibles</h2>                        
                    </div>
                    
                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                    <th>Costo ($)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="nuevo">
                                    <form method="POST">
                                        <td>
                                            <select name="id_transportador" required class="estado-reserva">
                                                <option value="">Seleccione Empresa</option>
                                                <?php while($t = mysqli_fetch_array($res_trans_nuevo)) {
                                                    echo "<option value='".$t['id_transportador']."'>".$t['nombre_empresa']."</option>";
                                                } ?>
                                            </select>
                                        </td>
                                        <td><input type="text" name="origen" placeholder="Origen" required></td>
                                        <td><input type="text" name="destino" placeholder="Destino" required></td>
                                        <td><input type="number" name="costo" placeholder="Precio" required></td>
                                        <td>
                                            <button type="submit" name="agregar" class="btn-primario">+ Agregar Ruta</button>
                                        </td>
                                    </form>
                                </tr>

                                <?php while ($fila = mysqli_fetch_array($res_rutas)) { ?>
                                    <tr>
                                        <form method="POST">
                                            <input type="hidden" name="id_servicio" value="<?php echo $fila['id_servicio']; ?>">
                                            <td>
                                                <select name="id_transportador" class="estado-reserva">
                                                    <?php 
                                                    // Volver a consultar transportadoras para cada fila de edición
                                                    $res_trans_edit = mysqli_query($conexion, $sql_trans);
                                                    while($te = mysqli_fetch_array($res_trans_edit)) {
                                                        $selected = ($te['id_transportador'] == $fila['id_transportador']) ? "selected" : "";
                                                        echo "<option value='".$te['id_transportador']."' $selected>".$te['nombre_empresa']."</option>";
                                                    } 
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="origen" value="<?php echo $fila['origen']; ?>"></td>
                                            <td><input type="text" name="destino" value="<?php echo $fila['destino']; ?>"></td>
                                            <td><input type="number" name="costo" value="<?php echo $fila['costo']; ?>"></td>
                                            <td style="display: flex; gap: 5px;">
                                                <button type="submit" name="actualizar" class="btn-primario" onclick="return confirm('¿Guardar cambios?')">Actualizar</button>
                                                <button type="submit" name="eliminar" class="btn-secundario" onclick="return confirm('¿Borrar esta ruta?')">Borrar</button>
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
</body>
</html>