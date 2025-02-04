<?php
session_start();
require_once("../conexion.php");

// Asegúrate de que el equipo_id está en la sesión
if (isset($_SESSION['equipo_id'])) {
    $equipo_id = $_SESSION['equipo_id'];

    try {
        // Consulta para obtener los jugadores del equipo actual
        $sql = "SELECT id, nombre, imagen FROM jugadores WHERE equipo_id = :equipo_id";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':equipo_id', $equipo_id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si se encontraron jugadores
        if ($stmt->rowCount() > 0) {
            echo "<h2>Jugadores del equipo</h2>";
            echo "<ul>";
            // Mostrar los jugadores con sus fotos
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>";
                echo "<img src='" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['nombre']) . "' width='100' height='100'> ";
                echo htmlspecialchars($row['nombre']);
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "No se encontraron jugadores para este equipo.";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No hay un equipo seleccionado en la sesión.";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .jugadores-lista {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
        justify-content: center;
    }

    .jugador {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        width: 150px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .foto-jugador {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .nombre-jugador {
        margin-top: 10px;
        font-size: 16px;
        font-weight: bold;
    }
</style>