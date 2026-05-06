<?php
// Inicio de sesión
session_start();

// limpia los datos en memoria
$_SESSION = array();

//  LIMPIEZA DE COOKIES (Seguridad Pro)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000, // Tiempo en el pasado para que el navegador la borre
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Destruimos formalmente la sesión en el servidor
session_destroy();

header("Location: ../auth/ingreso-pasajero.html");
exit();
?>