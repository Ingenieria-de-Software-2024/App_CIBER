<?php
function generarGraficaBarra(array $totales, string $titulo = "Total de incidentes") {
    $categorias = [
        "MALWARE", "PHISHING", "COMANDO Y\nCONTROL",
        "CRYTOMINERIA", "DENEGACION\nDE SERVICIOS", "INTENTOS DE\nCONEXIÓN\nBLOQUEADOS"
    ];

    $valores = [
        $totales['malware'] ?? 0,
        $totales['phishing'] ?? 0,
        $totales['comando'] ?? 0,
        $totales['crypto'] ?? 0,
        $totales['ddos'] ?? 0,
        $totales['bloqueadas'] ?? 0
    ];

    // Usar librería GD o Matplotlib vía subprocess (recomendado: matplotlib via CLI)
    // Este ejemplo requiere tener `matplotlib` y `python3` disponibles

    $data = [
        'titulo' => $titulo,
        'categorias' => $categorias,
        'valores' => $valores
    ];

    $jsonData = escapeshellarg(json_encode($data));
    $comando = "python3 /var/www/html/App_CIBER/includes/generar_grafico.py $jsonData";
    $output = shell_exec($comando);

    return $output ?: '';
}
