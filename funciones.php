<?php

// LLAMA A LA TABLA DE POSICIONES AUTOMÁTICA
function obtenerPosiciones($conexion) {
    $query = "
    SELECT 
        e.id AS equipo_id,
        e.nombre AS equipo,
        e.escudo,
        SUM(CASE WHEN p.equipo_local = e.id OR p.equipo_visitante = e.id THEN 1 ELSE 0 END) AS partidos_jugados,
        SUM(CASE WHEN p.equipo_local = e.id AND p.goles_local > p.goles_visitante THEN 1
                 WHEN p.equipo_visitante = e.id AND p.goles_visitante > p.goles_local THEN 1 ELSE 0 END) AS victorias,
        SUM(CASE WHEN p.goles_local = p.goles_visitante AND (p.equipo_local = e.id OR p.equipo_visitante = e.id) THEN 1 ELSE 0 END) AS empates,
        SUM(CASE WHEN p.equipo_local = e.id AND p.goles_local < p.goles_visitante THEN 1
                 WHEN p.equipo_visitante = e.id AND p.goles_visitante < p.goles_local THEN 1 ELSE 0 END) AS derrotas,
        SUM(CASE WHEN p.equipo_local = e.id THEN p.goles_local ELSE 0 END) +
        SUM(CASE WHEN p.equipo_visitante = e.id THEN p.goles_visitante ELSE 0 END) AS goles_a_favor,
        SUM(CASE WHEN p.equipo_local = e.id THEN p.goles_visitante ELSE 0 END) +
        SUM(CASE WHEN p.equipo_visitante = e.id THEN p.goles_local ELSE 0 END) AS goles_en_contra,
        (SUM(CASE WHEN p.equipo_local = e.id THEN p.goles_local ELSE 0 END) +
         SUM(CASE WHEN p.equipo_visitante = e.id THEN p.goles_visitante ELSE 0 END)) -
        (SUM(CASE WHEN p.equipo_local = e.id THEN p.goles_visitante ELSE 0 END) +
         SUM(CASE WHEN p.equipo_visitante = e.id THEN p.goles_local ELSE 0 END)) AS diferencia_goles,
        3 * SUM(CASE WHEN p.equipo_local = e.id AND p.goles_local > p.goles_visitante THEN 1
                     WHEN p.equipo_visitante = e.id AND p.goles_visitante > p.goles_local THEN 1 ELSE 0 END) +
        SUM(CASE WHEN p.goles_local = p.goles_visitante AND (p.equipo_local = e.id OR p.equipo_visitante = e.id) THEN 1 ELSE 0 END) AS puntos
    FROM equipos e
    LEFT JOIN partidos p ON e.id = p.equipo_local OR e.id = p.equipo_visitante
    GROUP BY e.id
    ORDER BY puntos DESC, diferencia_goles DESC, goles_a_favor DESC;
    ";

    return $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

?>