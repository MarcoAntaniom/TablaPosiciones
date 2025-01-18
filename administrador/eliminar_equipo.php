<?php
require_once("../conexion.php");

// Verificar si se ha pasado un id por GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar el equipo a eliminar
    $query = "SELECT * FROM equipos WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipo) {
        // Si no se encuentra el equipo, redirigir o mostrar un error
        echo "Equipo no encontrado.";
        exit;
    }

    // Eliminar el escudo del equipo (si existe) de la carpeta
    $escudo_path = "../escudos/" . $equipo['escudo'];
    if (file_exists($escudo_path)) {
        unlink($escudo_path);
    }

    // Eliminar el equipo de la base de datos
    $query = "DELETE FROM equipos WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirigir al listado de equipos con un mensaje de éxito
    echo "<script>
        alert('El equipo ha sido eliminado correctamente.');
        window.location.href = 'index.php'; // Redirigir al listado de equipos
    </script>";
    exit;
} else {
    echo "ID no especificado.";
    exit;
}