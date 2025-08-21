<?php

namespace Controllers;

use Model\CiberAtaque;
use MVC\Router;

class CiberAtaqueController
{
  public static function index(Router $router)
  {
    $router->render('ciberataque/index', []);
  }

  public static function buscarAPI()
  {
    try {
      $ataque = CiberAtaque::buscar();
      http_response_code(200);
      echo json_encode([
        'codigo' => 1,
        'mensaje' => 'Registro de tipos de Ciber-Ataques encontrado exitosamente',
        'detalle' => '',
        'datos' => $ataque
      ]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Error al buscar el registro de Ciber-Ataques',
        'detalle' => $e->getMessage(),
      ]);
    }
  }

  public static function guardarAPI()
  {
    $_POST['ata_nombre'] = htmlspecialchars($_POST['ata_nombre']);
    $_POST['ata_descripcion'] = htmlspecialchars($_POST['ata_descripcion']);
    $_POST['ata_situacion'] = 1;

    try {
      $CiberAtaque = new CiberAtaque($_POST);
      $resultado = $CiberAtaque->crear();
      http_response_code(200);
      echo json_encode([
        'codigo' => 1,
        'mensaje' => 'Tipo de Ciber-Ataque guardado exitosamente',
      ]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Error al guardar el registro de Ciber-Ataques',
        'detalle' => $e->getMessage(),
      ]);
    }
  }

  public static function modificarAPI()
  {
    $_POST['ata_nombre'] = htmlspecialchars($_POST['ata_nombre']);
    $_POST['ata_descripcion'] = htmlspecialchars($_POST['ata_descripcion']);
    $id = filter_var($_POST['ata_id'], FILTER_SANITIZE_NUMBER_INT);

    try {
      $Ataque = CiberAtaque::find($id);
      if (!$Ataque) {
        throw new \Exception("Registro de Ciber-Ataques no encontrado");
      }

      $_POST['ata_id'] = (int)$id;

      $Ataque->sincronizar($_POST);
      $Ataque->actualizar();
      http_response_code(200);
      echo json_encode([
        'codigo' => 1,
        'mensaje' => 'Tipo de Ciber-Ataque modificado exitosamente',
      ]);

    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Error al modificar el tipo de Ciber-Ataque operativo',
        'detalle' => $e->getMessage(),
      ]);
    }
  }

  public static function eliminarAPI()
  {
    $id = filter_var($_POST['ata_id'], FILTER_SANITIZE_NUMBER_INT);

    try {
      $CiberAtaque = CiberAtaque::find($id);
      if (!$CiberAtaque) {
        throw new \Exception("Sistema operativo no encontrado");
      }

      $CiberAtaque->sincronizar(['ata_situacion' => 0]);
      $CiberAtaque->actualizar();
      http_response_code(200);
      echo json_encode([
        'codigo' => 4,
        'mensaje' => 'Tipo de Ciber-Ataque eliminado exitosamente',
      ]);
      
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Error al eliminar el tipo de Ciber-Ataque',
        'detalle' => $e->getMessage(),
      ]);
    }
  }

}
