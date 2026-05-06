<?php
require_once '../main/PHP/conexion.php';
session_start();

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}
$id_logueado = $_SESSION['id_usuario'];

// --- LÓGICA PARA CREAR LA RESERVA ---
if (isset($_POST['reservar'])) {
    $id_servicio = mysqli_real_escape_string($conexion, $_POST['id_servicio']);
    $fecha_viaje = mysqli_real_escape_string($conexion, $_POST['fecha_viaje']);
    $horario     = mysqli_real_escape_string($conexion, $_POST['horario']);
    $asiento     = mysqli_real_escape_string($conexion, $_POST['asiento']);
    $estado      = "Pendiente";

    $fecha_registro = date('Y-m-d H:i:s'); 

    $sql_reserva = "INSERT INTO reserva (id_usuario, id_servicio, fecha_reserva, horario, asiento, estado) 
                    VALUES ('$id_logueado', '$id_servicio', '$fecha_registro', '$horario', '$asiento', '$estado')";

    if (mysqli_query($conexion, $sql_reserva)) {
        echo "<script>alert('¡Reserva realizada con éxito!'); window.location='mis-reservas.php';</script>";
    } else {
        echo "<script>alert('Error al procesar: " . mysqli_error($conexion) . "');</script>";
    }
}
// --- CONSULTA DE RUTAS ---
// JOIN, modificamos la consulta para que solo traiga servicios que tengan al menos una entrada en la tabla "reserva" (que es donde el admin pone los horarios/asientos)
$sql_rutas = "SELECT s.*, t.nombre_empresa 
              FROM servicio s 
              JOIN transportador t ON s.id_transportador = t.id_transportador
              WHERE EXISTS (
                  SELECT 1 FROM reserva r 
                  WHERE r.id_servicio = s.id_servicio
              )";
$res_rutas = mysqli_query($conexion, $sql_rutas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva - Terminal</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../main/CSS/cliente styles/reservas-cliente.css"> 
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
            <a href="reservas-cliente.php" class="active">Nueva Reserva</a>
            <a href="mis-reservas.php">Mis Reservas</a>
            <a href="cerrar-sesion-usuario.php" style="color: #ff4d4d;">Cerrar Sesión</a>                        
        </div>

        <div class="paragraphs">
            <div class="column">
                <main class="pagina-contenido">
                    <div class="pagina-encabezado">
                        <h2>Selecciona tu destino</h2>                        
                    </div>

                    <div class="rutas-flex">
                        <?php while ($ruta = mysqli_fetch_array($res_rutas)) { 
                            $id_ser = $ruta['id_servicio'];
                        ?>
                            <div class="ruta-card">
                                <div class="ruta-encabezado">
                                    <span class="ruta-origen"><?php echo $ruta['origen']; ?></span>
                                    <span class="ruta-flecha">→</span>
                                    <span class="ruta-destino"><?php echo $ruta['destino']; ?></span>
                                </div>
                                
                                <div class="ruta-info">
                                    <div class="ruta-dato">
                                        <span class="dato-label">Empresa</span>
                                        <span class="dato-valor"><?php echo $ruta['nombre_empresa']; ?></span>
                                    </div>
                                    <div class="ruta-dato">
                                        <span class="dato-label">Precio</span>
                                        <span class="dato-valor">$<?php echo number_format($ruta['costo'], 0, ',', '.'); ?></span>
                                    </div>
                                </div>

                                <form method="POST" class="reserva-form">
                                    <input type="hidden" name="id_servicio" value="<?php echo $id_ser; ?>">
                                    
                                    <label>Fecha de Viaje:</label>
                                    <input type="date" name="fecha_viaje" required class="estado-reserva" 
                                        min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                                    
                                    <label>Horario Disponible:</label>
                                    <select name="horario" class="estado-reserva" required>
                                        <option value="">Seleccione Horario</option>
                                        <?php 
                                        // Obtenemos los horarios ÚNICOS que ya existen en la tabla "reserva" para este servicio
                                        $res_h = mysqli_query($conexion, "SELECT DISTINCT horario FROM reserva WHERE id_servicio = '$id_ser'");
                                        
                                        if(mysqli_num_rows($res_h) > 0) {
                                            while($h = mysqli_fetch_array($res_h)) {
                                                // Formateo de hora para que sea más legible
                                                $hora_formateada = date('h:i A', strtotime($h['horario']));
                                                echo "<option value='".$h['horario']."'>".$hora_formateada."</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled>No hay horarios registrados</option>";
                                        }
                                        ?>
                                    </select>

                                    <label>Selecciona tu Asiento:</label>
                                    <select name="asiento" class="estado-reserva" required>
                                        <option value="">-- Seleccione Asiento --</option>
                                        <?php 
                                        // Obtenemos los asientos registrados en la tabla "reserva" para este servicio
                                        // IMPORTANTE: Aquí mostramos los asientos que existen en la base de datos de la tabla "reserva"
                                        $res_a = mysqli_query($conexion, "SELECT DISTINCT asiento FROM reserva WHERE id_servicio = '$id_ser'");
                                        
                                        while($a = mysqli_fetch_array($res_a)) {
                                            echo "<option value='".$a['asiento']."'>Asiento ".$a['asiento']."</option>";
                                        }
                                        ?>
                                    </select>

                                    <button type="submit" name="reservar" class="btn-primario">
                                        Confirmar Reserva
                                    </button>
                                </form>
                            </div>
                        <?php } ?>
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