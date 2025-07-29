<?php

namespace Controllers;

use Exception;
use Model\Bitacora;
use MVC\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

require_once __DIR__ . '/../includes/resumen_imagen.php';
require_once __DIR__ . '/../includes/funciones.php';

class BitacoraController
{
    private static function sendJsonResponse(int $statusCode, int $code, string $message, array $data = [], string $detail = '')
    {
        http_response_code($statusCode);
        echo json_encode([
            'codigo' => $code,
            'mensaje' => $message,
            'detalle' => $detail,
            'datos' => $data
        ]);
        exit;
    }

    public static function index(Router $router)
    {
        $router->render('bitacora/index', []);
    }

    public static function buscarAPI()
    {
        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;

            if ($fechaInicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
                self::sendJsonResponse(400, 0, 'Formato de fecha de inicio inválido. Use YYYY-MM-DD.', [], 'Fecha de inicio: ' . $fechaInicio);
            }
            if ($fechaFin && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                self::sendJsonResponse(400, 0, 'Formato de fecha de fin inválido. Use YYYY-MM-DD.', [], 'Fecha de fin: ' . $fechaFin);
            }

            $registros = Bitacora::buscarActivos($fechaInicio, $fechaFin);

            if (empty($registros)) {
                self::sendJsonResponse(200, 2, 'No se encontraron registros para la búsqueda.', []);
            } else {
                self::sendJsonResponse(200, 1, 'Datos encontrados', $registros);
            }
        } catch (Exception $e) {
            self::sendJsonResponse(500, 0, 'Error interno al buscar los registros', [], $e->getMessage());
        }
    }


    public static function guardarAPI()
    {
        try {
            if (!isset($_POST['bita_fecha']) || empty($_POST['bita_fecha'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El campo fecha (bita_fecha) es requerido.',
                    'detalle' => 'Fecha ausente.'
                ]);
                exit;
            }

            // Validar formato: debe venir en yyyy-mm-dd
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['bita_fecha'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Formato de fecha inválido. Use yyyy-mm-dd.',
                    'detalle' => 'Recibida: ' . $_POST['bita_fecha']
                ]);
                exit;
            }

            $datosValidados = [];
            $camposNumericos = [
                'bita_malware',
                'bita_pishing',
                'bita_coman_cont',
                'bita_cryptomineria',
                'bita_ddos',
                'bita_conex_bloq'
            ];

            foreach ($camposNumericos as $campo) {
                if (!isset($_POST[$campo]) || filter_var($_POST[$campo], FILTER_VALIDATE_INT) === false) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "El campo '{$campo}' es requerido y debe ser un número entero válido.",
                        'detalle' => "Valor inválido para {$campo}: " . ($_POST[$campo] ?? 'ausente')
                    ]);
                    exit;
                }
                $datosValidados[$campo] = (int)$_POST[$campo];
            }

            $datosValidados['bita_fecha'] = $_POST['bita_fecha'];
            $datosValidados['bita_total'] = array_sum($datosValidados);
            $datosValidados['bita_situacion'] = 1;

            $bitacora = new Bitacora($datosValidados);
            $resultado = $bitacora->crear();

            header('Content-Type: application/json');
            if ($resultado) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Registro guardado correctamente'
                ]);
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo guardar el registro.',
                    'detalle' => 'Error desconocido al insertar.'
                ]);
            }
            exit;

        } catch (Exception $e) {
            error_log("❌ ERROR al guardar bitácora: " . $e->getMessage());

            // ✅ Asegurarse de devolver JSON con error 500
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno al guardar el registro.',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }


    public static function exportarXLSX()
    {
        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;

            if ($fechaInicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) throw new Exception("Fecha de inicio inválida.");
            if ($fechaFin && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) throw new Exception("Fecha de fin inválida.");

            $registros = Bitacora::buscarActivos($fechaInicio, $fechaFin);
            if (empty($registros)) throw new Exception("No hay datos para exportar.");

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("Bitácora Ciber");

            $sheet->fromArray([
                'Fecha', 'Malware', 'Phishing', 'Comando y Control', 'Cryptominería', 'DDoS', 'Conexiones Bloqueadas', 'Total'
            ], null, 'A1');

            $row = 2;
            foreach ($registros as $registro) {
                $sheet->fromArray([
                    $registro->bita_fecha,
                    $registro->bita_malware,
                    $registro->bita_pishing,
                    $registro->bita_coman_cont,
                    $registro->bita_cryptomineria,
                    $registro->bita_ddos,
                    $registro->bita_conex_bloq,
                    $registro->bita_total
                ], null, 'A' . $row++);
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename*=UTF-8''bitacora.xlsx");
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error al exportar: " . $e->getMessage();
            exit;
        }
    }

    public static function exportarPDF()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;
            $nombreArchivo = $_GET['nombre'] ?? 'historial_incidentes';

            if ($fechaInicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) throw new Exception("Fecha de inicio inválida.");
            if ($fechaFin && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) throw new Exception("Fecha de fin inválida.");

            $registros = Bitacora::buscarActivos($fechaInicio, $fechaFin);
            if (empty($registros)) throw new Exception("No hay datos para exportar.");

            $tituloGrafico = $fechaInicio && $fechaFin
                ? "Del " . date('d/m/Y', strtotime($fechaInicio)) . " al " . date('d/m/Y', strtotime($fechaFin))
                : "Todos los Registros";

            // Pasar el título como variable global para usarlo en plantilla_pdf.php
            $GLOBALS['tituloGrafico'] = $tituloGrafico;


            ob_start();
            include_once __DIR__ . '/../views/bitacora/plantilla_pdf.php';
            $html = ob_get_clean();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"{$nombreArchivo}.pdf\"");

            echo $dompdf->output();
            exit;

        } catch (Exception $e) {
            http_response_code(500);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header("Content-Type: application/json");
                echo json_encode([
                    'error' => true,
                    'mensaje' => 'Error al generar PDF',
                    'detalle' => $e->getMessage()
                ]);
            } else {
                echo "<pre>Error al generar PDF: " . $e->getMessage() . "</pre>";
            }
            exit;
        }
    }


    public static function exportarImagenResumen()
    {
        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;

            if ($fechaInicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
                throw new Exception("Fecha de inicio inválida.");
            }
            if ($fechaFin && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                throw new Exception("Fecha de fin inválida.");
            }

            $registros = Bitacora::buscarActivos($fechaInicio, $fechaFin);
            if (empty($registros)) throw new Exception("No hay datos para generar la imagen.");

            $mesInicio = date('Y-m-01', strtotime($fechaInicio));
            $mesFin = date('Y-m-t', strtotime($fechaInicio));
            $registrosMes = Bitacora::buscarActivos($mesInicio, $mesFin);

            $totalesPeriodo = calcularTotales($registros);
            $totalesMes = calcularTotales($registrosMes);

            $imageData = generarResumenImagen($totalesPeriodo, $totalesMes, $fechaInicio, $fechaFin);
            $base64 = base64_encode($imageData);

            header('Content-Type: application/json');
            echo json_encode(['imagen' => 'data:image/png;base64,' . $base64]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'mensaje' => 'No se pudo generar la imagen.']);
            exit;
        }
    }





}
