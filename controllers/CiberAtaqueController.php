<?php

namespace Controllers;

use MVC\Router;

class CiberAtaqueController
  {
    public static function index(Router $router){
        $router->render('ciberataque/index', []);
    }

}