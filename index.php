<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['tipo_usuario_id'])) {
    // Si no hay sesión iniciada, redirigir al login
    header("Location: login.php");
    exit();
}

// Obtener el tipo de usuario de la sesión
$tipo_usuario_id = $_SESSION['tipo_usuario_id'];

// Redirigir según el tipo de usuario
switch ($tipo_usuario_id) {
    case 1: // Administrador
        header("Location: administrador/index.php");
        break;
    case 2: // Usuario
        header("Location: usuario/dashboard.php");
        break;
    default:
        // Si el tipo de usuario no es reconocido, redirigir al login
        header("Location: login.php");
        break;
}

exit();
?>