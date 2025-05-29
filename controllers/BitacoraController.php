<?php

namespace Controllers;

use MVC\Router;

class BitacoraController {
    public static function index(Router $router){
        $router->render('bitacora/index', []);
    }

}