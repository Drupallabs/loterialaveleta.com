<?php
namespace Drupal\empresas\Controller;

use Drupal\Core\Controller\ControllerBase;

class EmpresasController extends ControllerBase {

  public function index() {
    return array(
      //Your theme hook name
      '#theme' => 'empresas',
    );
  }
}
