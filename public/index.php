<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\BitacoraController;
use Controllers\CiberAtaqueController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

// BITÁCORA
$router->get('/bitacora', [BitacoraController::class,'index']);
$router->get('/API/bitacora/buscar', [BitacoraController::class,'buscarAPI']);
$router->post('/API/bitacora/guardar', [BitacoraController::class,'guardarAPI']);

// CIBERATAQUES
$router->get('/ciberataque', [CiberAtaqueController::class,'index']);
$router->post('/API/ciberataque/guardar', [CiberAtaqueController::class,'guardarAPI']);
$router->post('/API/ciberataque/modificar', [CiberAtaqueController::class,'modificarAPI']);
$router->get('/API/ciberataque/buscar', [CiberAtaqueController::class,'buscarAPI']);
$router->post('/API/ciberataque/eliminar', [CiberAtaqueController::class,'eliminarAPI']);



$router->comprobarRutas();
