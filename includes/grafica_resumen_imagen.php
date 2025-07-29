<?php

function generarResumenBitacoraImagen(array $registros, string $fechaInicio, string $fechaFin)
{
    $width = 1200;
    $height = 500;
    $img = imagecreatetruecolor($width, $height);

    // Colores
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);
    $blue = imagecolorallocate($img, 0, 102, 204);
    $gray = imagecolorallocate($img, 230, 230, 230);
    $border = imagecolorallocate($img, 180, 180, 180);

    // Fuente
    $font = __DIR__ . "/DejaVuSans.ttf"; // Usa una fuente del sistema
    if (!file_exists($font)) $font = dirname(__FILE__) . "/../public/fonts/DejaVuSans.ttf";

    imagefill($img, 0, 0, $white);

    // Encabezado
    $titulo = "Resumen Bitácora de Incidentes";
    imagettftext($img, 18, 0, 30, 50, $black, $font, $titulo);

    $periodoTexto = "Período: " . date('d/m/Y', strtotime($fechaInicio)) . " a " . date('d/m/Y', strtotime($fechaFin));
    imagettftext($img, 14, 0, 30, 80, $black, $font, $periodoTexto);

    // Totales
    $categorias = ['Malware', 'Phishing', 'Comando y Control', 'Cryptominería', 'DDoS', 'Conex. Bloqueadas'];
    $claves = ['bita_malware', 'bita_pishing', 'bita_coman_cont', 'bita_cryptomineria', 'bita_ddos', 'bita_conex_bloq'];

    $totales = array_fill_keys($claves, 0);
    foreach ($registros as $r) {
        foreach ($claves as $clave) $totales[$clave] += $r->$clave;
    }

    // Tabla visual
    $startY = 140;
    $rowHeight = 40;
    $colX = [30, 400, 700];
    imagettftext($img, 13, 0, $colX[0], $startY, $blue, $font, "Categoría");
    imagettftext($img, 13, 0, $colX[1], $startY, $blue, $font, "Total");
    imagettftext($img, 13, 0, $colX[2], $startY, $blue, $font, "Porcentaje");

    $i = 1;
    $totalGeneral = array_sum($totales);
    foreach ($categorias as $index => $cat) {
        $clave = $claves[$index];
        $total = $totales[$clave];
        $y = $startY + ($i * $rowHeight);

        // Rectángulo de fila
        imagefilledrectangle($img, 20, $y - 25, $width - 20, $y + 10, $gray);
        imagerectangle($img, 20, $y - 25, $width - 20, $y + 10, $border);

        imagettftext($img, 12, 0, $colX[0], $y, $black, $font, $cat);
        imagettftext($img, 12, 0, $colX[1], $y, $black, $font, (string)$total);
        $porcentaje = $totalGeneral > 0 ? round(($total / $totalGeneral) * 100, 1) . '%' : '0%';
        imagettftext($img, 12, 0, $colX[2], $y, $black, $font, $porcentaje);

        $i++;
    }

    // Total general
    imagettftext($img, 14, 0, 30, $y + 60, $black, $font, "Total acumulado del mes: $totalGeneral");

    return $img;
}
