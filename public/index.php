<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\BitacoraController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

// BITÁCORA
$router->get('/bitacora', [BitacoraController::class,'index']);
$router->get('/API/bitacora/buscar', [BitacoraController::class,'buscarAPI']);
$router->post('/API/bitacora/guardar', [BitacoraController::class,'guardarAPI']);

// ✅ RUTA NUEVA PARA EXPORTAR XLSX y PDF
$router->get('/API/bitacora/exportar-imagen', [BitacoraController::class, 'exportarImagen']);
$router->get('/API/bitacora/exportar-xlsx', [BitacoraController::class, 'exportarXLSX']);
$router->get('/API/bitacora/exportar-pdf', [BitacoraController::class, 'exportarPDF']);

$router->comprobarRutas();
