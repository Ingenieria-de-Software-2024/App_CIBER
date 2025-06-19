<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\BitacoraController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);
$router->comprobarRutas();
$router->get('/', [AppController::class,'index']);

//BITACORA
$router->get('/bitacora', [BitacoraController::class,'index']);

