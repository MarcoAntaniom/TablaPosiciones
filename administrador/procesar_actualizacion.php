<?php
require_once("../conexion.php"); // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todos los datos necesarios estén presentes
    $partidoId = isset($_POST['partido_id']) ? intval($_POST['partido_id']) : null;
    $golesLocal = isset($_POST['goles_local']) ? intval($_POST['goles_local']) : null;
    $golesVisitante = isset($_POST['goles_visitante']) ? intval($_POST['goles_visitante']) : null;

    if ($partidoId !== null && $golesLocal !== null && $golesVisitante !== null) {
        try {
            // Preparar la consulta para actualizar los goles
            $query = "UPDATE partidos SET goles_local = :goles_local, goles_visitante = :goles_visitante WHERE id = :partido_id";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':goles_local', $golesLocal, PDO::PARAM_INT);
            $stmt->bindParam(':goles_visitante', $golesVisitante, PDO::PARAM_INT);
            $stmt->bindParam(':partido_id', $partidoId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<script>alert('Goles actualizados correctamente.'); window.location.href='actualizar_goles.php';</script>";
            } else {
                echo "<script>alert('Error al actualizar los goles.'); window.history.back();</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error en la base de datos: " . $e->getMessage() . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Método no permitido.'); window.history.back();</script>";
}
?>
