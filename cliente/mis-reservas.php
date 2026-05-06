<?php
require_once '../main/PHP/conexion.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}
$id_logueado = $_SESSION['id_usuario'];

// --- LÓGICA PARA CANCELAR RESERVA (Update) ---
if (isset($_POST['cancelar_id'])) {
    $id_canc = mysqli_real_escape_string($conexion, $_POST['cancelar_id']);
    
    // Solo permitimos cancelar si el estado actual es "Pendiente'
    $sql_cancelar = "UPDATE reserva SET estado = 'Cancelada' 
                     WHERE id_reserva = '$id_canc' AND id_usuario = '$id_logueado'";
    
    if (mysqli_query($conexion, $sql_cancelar)) {
        echo "<script>alert('Reserva cancelada correctamente.'); window.location='mis-reservas.php';</script>";
    }
}
// --- CONSULTA DE MIS RESERVAS ---
// JOIN, unimos la tabla de "reserva" con "servicio" para obtener origen, destino y costo
$sql_mis_viajes = "SELECT r.*, s.origen, s.destino, s.costo, t.nombre_empresa 
                   FROM reserva r
                   JOIN servicio s ON r.id_servicio = s.id_servicio
                   JOIN transportador t ON s.id_transportador = t.id_transportador
                   WHERE r.id_usuario = '$id_logueado'
                   ORDER BY r.fecha_reserva DESC";
$res_viajes = mysqli_query($conexion, $sql_mis_viajes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva - Terminal</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/cliente styles/mis-reservas.css"> 
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
                <p class="banner-subtitulo">Viaja con nosotros</p>
                <h1>Rutas Disponibles</h1>
            </div>
        </div>

        <div id="menu">
            <a href="cliente.php">Mi Perfil</a>
            <a href="reservas-cliente.php">Nueva Reserva</a>
            <a href="mis-reservas.php" class="active">Mis Reservas</a>
            <a href="cerrar-sesion-usuario.php" style="color: #ff4d4d;">Cerrar Sesión</a>                        
        </div>

        <div class="paragraphs">
            <div class="column">
                <main class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Historial de mis Viajes</h2>
                    </div>

                    <div class="tabla-wrapper">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ruta</th>
                                    <th>Empresa</th>
                                    <th>Fecha/Hora Viaje</th>
                                    <th>Asiento</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($res_viajes) > 0) {
                                    while($viaje = mysqli_fetch_array($res_viajes)) {                                        
                                        $clase_estado = "estado-" . strtolower($viaje['estado']);
                                ?>
                                    <tr>
                                        <td>#<?php echo $viaje['id_reserva']; ?></td>
                                        <td><strong><?php echo $viaje['origen'] . " → " . $viaje['destino']; ?></strong></td>
                                        <td><?php echo $viaje['nombre_empresa']; ?></td>
                                        <td>
                                            <?php 
                                                echo date('d/m/Y', strtotime($viaje['fecha_reserva'])) . "<br>";
                                                echo date('h:i A', strtotime($viaje['horario'])); 
                                            ?>
                                        </td>
                                        <td><?php echo $viaje['asiento']; ?></td>
                                        <td><span class="badge <?php echo $clase_estado; ?>"><?php echo $viaje['estado']; ?></span></td>
                                        <td>
                                            <?php if($viaje['estado'] == 'Pendiente') { ?>
                                                <form method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                                    <input type="hidden" name="cancelar_id" value="<?php echo $viaje['id_reserva']; ?>">
                                                    <button type="submit" class="btn-primario">Cancelar</button>
                                                </form>
                                            <?php } else { echo "-"; } ?>
                                        </td>
                                    </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='7'>Aún no tienes reservas realizadas.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>

        <footer class="footer">
            <p>Terminal de Transporte Salitre Bogotá &copy; 2026</p>
        </footer>
    </div>
</body>
</html>