<?php

function generarResumenImagen($totalesPeriodo, $totalesMes, $fechaInicio, $fechaFin)
{
    ob_start(); // Inicia el buffer para capturar la imagen como string

    // Crear imagen
    $ancho = 900;
    $alto = 350;
    $imagen = imagecreatetruecolor($ancho, $alto);

    // Colores
    $blanco = imagecolorallocate($imagen, 255, 255, 255);
    $negro  = imagecolorallocate($imagen, 0, 0, 0);
    $azul   = imagecolorallocate($imagen, 0, 102, 204);

    // Fondo
    imagefilledrectangle($imagen, 0, 0, $ancho, $alto, $blanco);

    // Título
    imagestring($imagen, 5, 280, 10, "RESUMEN DE CIBERAMENAZAS", $negro);
    $rango = "Del " . date('d/m/Y', strtotime($fechaInicio)) . " al " . date('d/m/Y', strtotime($fechaFin));
    imagestring($imagen, 3, 320, 30, $rango, $negro);

    // Encabezado
    imagestring($imagen, 3, 30, 60, "CIBER AMENAZA", $azul);
    imagestring($imagen, 3, 350, 60, "MITIGADOS PERIODO", $azul);
    imagestring($imagen, 3, 550, 60, "MITIGADOS MES", $azul);

    // Datos
    $datos = [
        'MALWARE' => 'malware',
        'PHISHING' => 'phishing',
        'COMANDO Y CONTROL' => 'comando',
        'CRYPTOMINERÍA' => 'crypto',
        'DENEGACION DE SERVICIOS' => 'ddos',
        'INTENTOS DE CONEXIÓN' => 'bloqueadas'
    ];

    $y = 90;
    foreach ($datos as $etiqueta => $clave) {
        imagestring($imagen, 3, 30, $y, $etiqueta, $negro);
        imagestring($imagen, 3, 350, $y, number_format($totalesPeriodo[$clave]), $negro);
        imagestring($imagen, 3, 550, $y, number_format($totalesMes[$clave]), $negro);
        $y += 30;
    }

    // Totales
    imagestring($imagen, 4, 30, $y + 10, "TOTAL AMENAZAS MITIGADAS", $negro);
    imagestring($imagen, 4, 350, $y + 10, number_format($totalesPeriodo['total']), $negro);
    imagestring($imagen, 4, 550, $y + 10, number_format($totalesMes['total']), $negro);

    // Generar imagen en buffer
    imagepng($imagen);
    imagedestroy($imagen);

    return ob_get_clean(); // Devuelve el contenido del buffer
}
