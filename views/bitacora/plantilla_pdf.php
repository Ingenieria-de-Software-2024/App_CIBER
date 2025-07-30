<?php
use Dompdf\Dompdf;

// Rutas y datos
$logoPath = __DIR__ . '/../../public/images/LogoCiber.png';
$logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

$registros = $registros ?? [];
$fechaInicio = $_GET['fecha_inicio'] ?? null;
$fechaFin = $_GET['fecha_fin'] ?? null;
$generado = date('d/m/Y');

// Preparar título del período
$periodo = ($fechaInicio && $fechaFin)
    ? "De " . date('d/m/Y', strtotime($fechaInicio)) . " a " . date('d/m/Y', strtotime($fechaFin))
    : "Todos los registros";

// Totales por categoría
$totales = [
    'malware' => 0,
    'phishing' => 0,
    'comando' => 0,
    'crypto' => 0,
    'ddos' => 0,
    'bloqueadas' => 0,
    'total' => 0
];
foreach ($registros as $r) {
    $totales['malware'] += $r->bita_malware;
    $totales['phishing'] += $r->bita_pishing;
    $totales['comando'] += $r->bita_coman_cont;
    $totales['crypto'] += $r->bita_cryptomineria;
    $totales['ddos'] += $r->bita_ddos;
    $totales['bloqueadas'] += $r->bita_conex_bloq;
    $totales['total'] += $r->bita_total;
}

// Generar gráfica
require_once __DIR__ . '/../../includes/grafica_barra.php';
$titulo = isset($GLOBALS['tituloGrafico']) ? $GLOBALS['tituloGrafico'] : 'Gráfico de Incidentes';
$tituloGrafico = "ACUMULADO DEL ";
if ($fechaInicio && $fechaFin) {
    $tituloGrafico .= "PERÍODO DEL " . date('d/m/Y', strtotime($fechaInicio)) . " AL " . date('d/m/Y', strtotime($fechaFin));
} else {
    $tituloGrafico .= "AÑO " . date('Y');
}
$graficaBase64 = generarGraficaBarra($totales, $tituloGrafico);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        .logo { width: 80px; }
        .encabezado { text-align: center; margin-bottom: 20px; }
        .detalle { font-size: 14px; margin-top: 5px; margin-bottom: 5px; }

        table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: center; }
        th { background-color: #1e63b0; color: #fff; }
        tfoot td { font-weight: bold; background-color: #f2f2f2; }

        .grafica { text-align: center; margin-top: 30px; }
        .grafico { width: 100%; max-height: 280px; }
    </style>
</head>
<body>

<div class="encabezado">
    <img src="data:image/png;base64,<?= $logoBase64 ?>" class="logo">
    <h2>Historial de Incidentes Cibernéticos</h2>
    <div class="detalle"><?= $periodo ?></div>
    <div class="detalle">Generado el <?= $generado ?></div>
</div>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Fecha</th>
            <th>Malware</th>
            <th>Phishing</th>
            <th>Comando y Control</th>
            <th>Cryptominería</th>
            <th>DDoS</th>
            <th>Conexiones Bloqueadas</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $index => $r): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $r->bita_fecha ?></td>
            <td><?= $r->bita_malware ?></td>
            <td><?= $r->bita_pishing ?></td>
            <td><?= $r->bita_coman_cont ?></td>
            <td><?= $r->bita_cryptomineria ?></td>
            <td><?= $r->bita_ddos ?></td>
            <td><?= $r->bita_conex_bloq ?></td>
            <td><?= $r->bita_total ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">TOTALES</td>
            <td><?= $totales['malware'] ?></td>
            <td><?= $totales['phishing'] ?></td>
            <td><?= $totales['comando'] ?></td>
            <td><?= $totales['crypto'] ?></td>
            <td><?= $totales['ddos'] ?></td>
            <td><?= $totales['bloqueadas'] ?></td>
            <td><?= $totales['total'] ?></td>
        </tr>
    </tfoot>
</table>

<?php if ($graficaBase64): ?>
    <div class="grafica">
        <h2>Total de incidentes</h2>
        <img class="grafico" src="data:image/png;base64,<?= $graficaBase64 ?>">
    </div>
<?php endif; ?>

</body>
</html>
