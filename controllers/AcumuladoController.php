<?php

namespace Controllers;

use Exception;
use Model\Acumulado;
use MVC\Router;

class AcumuladoController
{


    public static function index(Router $router)
    {
        $router->render('acumulado/index', [
            
        ]);
    }
}
