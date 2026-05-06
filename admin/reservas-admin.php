<?php
require_once '../main/PHP/conexion.php';

// --- LÓGICA PARA AGREGAR NUEVA RESERVA ---
if (isset($_POST['agregar_reserva'])) {
    $user = $_POST['id_usuario'];
    $serv = $_POST['id_servicio'];
    $asiento = mysqli_real_escape_string($conexion, $_POST['asiento']);
    $estado = $_POST['estado'];
    $horario = $_POST['horario']; 
    
    // Captura de fecha manual
    $fecha = $_POST['fecha_manual']; 

    $sql_insert = "INSERT INTO reserva (id_usuario, id_servicio, fecha_reserva, horario, asiento, estado) 
                   VALUES ('$user', '$serv', '$fecha', '$horario', '$asiento', '$estado')";
    
    if (mysqli_query($conexion, $sql_insert)) {
        echo "<script>alert('Reserva creada con éxito'); window.location='reservas-admin.php';</script>";
    } else {
        echo "<script>alert('Error al crear reserva: " . mysqli_error($conexion) . "');</script>";
    }
}

// --- LÓGICA PARA ACTUALIZAR ESTADO ---
if (isset($_POST['actualizar_estado'])) {
    $id_res = $_POST['id_reserva'];
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['nuevo_estado']);
    $sql_update = "UPDATE reserva SET estado = '$nuevo_estado' WHERE id_reserva = '$id_res'";
    mysqli_query($conexion, $sql_update);
    echo "<script>window.location='reservas-admin.php';</script>";
}

// --- LÓGICA PARA ELIMINAR RESERVA ---
if (isset($_POST['eliminar_reserva'])) {
    $id_res = $_POST['id_reserva'];
    $sql_delete = "DELETE FROM reserva WHERE id_reserva = '$id_res'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        echo "<script>alert('Reserva eliminada correctamente'); window.location='reservas-admin.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar');</script>";
    }
}

// --- CONSULTAS PARA LOS SELECTS (menús desplegables)---
$usuarios_res = mysqli_query($conexion, "SELECT id_usuario, nombre FROM usuario WHERE rol != 'admin'");
$servicios_res = mysqli_query($conexion, "SELECT s.id_servicio, s.ruta, t.nombre_empresa 
                                          FROM servicio s 
                                          INNER JOIN transportador t ON s.id_transportador = t.id_transportador");

// --- CONSULTA MAESTRA PARA LA TABLA ---
$sql_tabla = "SELECT r.id_reserva, u.nombre AS pasajero, s.ruta, t.nombre_empresa, 
                      r.fecha_reserva, r.horario, r.asiento, r.estado 
              FROM reserva r
              INNER JOIN usuario u ON r.id_usuario = u.id_usuario
              INNER JOIN servicio s ON r.id_servicio = s.id_servicio
              INNER JOIN transportador t ON s.id_transportador = t.id_transportador
              ORDER BY r.id_reserva DESC";
$res_reservas = mysqli_query($conexion, $sql_tabla);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Reservas Globales</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/admin styles/reservas-admin.css">    
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
                <p class="banner-subtitulo">Gestión de Reservas</p>
                <h1>Reservas Globales</h1>
            </div>
        </div>

        <div id="menu">
            <a href="administrador.php">Usuarios</a>
            <a href="transportadoras-admin.php">Transportadoras</a>
            <a href="rutas-admin.php">Rutas y Servicios</a>
            <a href="reservas-admin.php" class="active">Reservas Globales</a>
            <a href="cerrar-sesion-admin.php" style="color: #ff4d4d;">Cerrar Sesión</a>
        </div>

        <div class="paragraphs">
            <div class="column">
                <div class="pagina-contenido">
                    
                    <div class="pagina-encabezado">
                        <h2>Registrar Nueva Reserva Manual</h2>
                    </div>
                    <div class="tabla-wrapper">
                        <form method="POST">
                            <table class="tabla">
                                <thead>
                                    <tr>
                                        <th>Pasajero</th>
                                        <th>Servicio / Ruta</th>
                                        <th>Fecha</th> <th>Horario</th>
                                        <th>Asiento</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="id_usuario" required class="estado-reserva">
                                                <option value="">Seleccione Pasajero</option>
                                                <?php while($u = mysqli_fetch_array($usuarios_res)) {
                                                    echo "<option value='".$u['id_usuario']."'>".$u['nombre']."</option>";
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="id_servicio" required class="estado-reserva">
                                                <option value="">Seleccione Ruta</option>
                                                <?php while($s = mysqli_fetch_array($servicios_res)) {
                                                    echo "<option value='".$s['id_servicio']."'>".$s['ruta']." (".$s['nombre_empresa'].")</option>";
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" name="fecha_manual" required class="estado-reserva">
                                        </td>
                                        <td>
                                            <input type="time" name="horario" required class="estado-reserva">
                                        </td>
                                        <td><input type="text" name="asiento" placeholder="Ej: A12" required></td>
                                        <td>
                                            <select name="estado" class="estado-reserva">
                                                <option value="Pendiente">Pendiente</option>
                                                <option value="Confirmada">Confirmada</option>
                                            </select>
                                        </td>
                                        <td><button type="submit" name="agregar_reserva" class="btn-primario">+ Crear</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <br><hr><br>

                    <div class="pagina-encabezado">
                        <h2>Listado de Reservas</h2>
                    </div>
                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pasajero</th>
                                    <th>Ruta / Empresa</th>
                                    <th>Fecha / Horario / Asiento</th>
                                    <th>Estado Actual</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($r = mysqli_fetch_array($res_reservas)) { ?>
                                    <tr>
                                        <td>#<?php echo $r['id_reserva']; ?></td>
                                        <td><?php echo $r['pasajero']; ?></td>
                                        <td>
                                            <strong><?php echo $r['ruta']; ?></strong><br>
                                            <small><?php echo $r['nombre_empresa']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($r['fecha_reserva'])); ?><br>
                                            <small>Hora: <strong><?php echo date('h:i A', strtotime($r['horario'])); ?></strong></small><br>
                                            <small>Asiento: <?php echo $r['asiento']; ?></small>
                                        </td>
                                        <td><strong><?php echo $r['estado']; ?></strong></td>
                                        <td class="acciones-container">
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_reserva" value="<?php echo $r['id_reserva']; ?>">
                                                <select name="nuevo_estado" class="estado-reserva">
                                                    <option value="Pendiente" <?php if($r['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                                                    <option value="Confirmada" <?php if($r['estado']=='Confirmada') echo 'selected'; ?>>Confirmada</option>
                                                    <option value="Cancelada" <?php if($r['estado']=='Cancelada') echo 'selected'; ?>>Cancelada</option>
                                                </select>
                                                <button type="submit" name="actualizar_estado" class="btn-primario">OK</button>
                                            </form>

                                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?');">
                                                <input type="hidden" name="id_reserva" value="<?php echo $r['id_reserva']; ?>">
                                                <button type="submit" name="eliminar_reserva" class="btn-secundario">Eliminar</button>
                                            </form>
                                        </td>
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