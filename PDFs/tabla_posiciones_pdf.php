<?php
require_once("../vendor/autoload.php");
require_once("../conexion.php");
require_once("../funciones.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configurar opciones de Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true); // Habilitar carga de recursos remotos
$dompdf = new Dompdf($options);

// Obtener datos de la tabla de posiciones
$tabla_posiciones = obtenerPosiciones($conexion);

// Logo de la liga en base64
$logo_base64 = base64_encode(file_get_contents("http://localhost/TablaPosiciones/Logo.png"));
$liga_logo = 'data:image/png;base64,' . $logo_base64;

// Generar el contenido HTML del PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Posiciones</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <img src="'.$liga_logo.'" alt="Logo de la Liga" style="width: 100px; margin-bottom: 20px;">
    <h1>Tabla de Posiciones</h1>
    <table>
        <tr>
            <th>#</th>
            <th>Equipo</th>
            <th>PJ</th>
            <th>PG</th>
            <th>PE</th>
            <th>PP</th>
            <th>GF</th>
            <th>GC</th>
            <th>DG</th>
            <th>PTS</th>
        </tr>';

$posicion = 1;
foreach ($tabla_posiciones as $row) {
    $html .= "<tr>
                <td>{$posicion}</td>
                <td>{$row['equipo']}</td>
                <td>{$row['partidos_jugados']}</td>
                <td>{$row['victorias']}</td>
                <td>{$row['empates']}</td>
                <td>{$row['derrotas']}</td>
                <td>{$row['goles_a_favor']}</td>
                <td>{$row['goles_en_contra']}</td>
                <td>{$row['diferencia_goles']}</td>
                <td>{$row['puntos']}</td>
              </tr>";
    $posicion++;
}

$html .= '</table>
</body>
</html>';

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

// Renderizar el PDF
try {
    $dompdf->render();
} catch (Exception $e) {
    echo "Error al generar el PDF: " . $e->getMessage();
    exit;
}

// Enviar PDF al navegador
$dompdf->stream("tabla_posiciones.pdf", ["Attachment" => false]);
?>