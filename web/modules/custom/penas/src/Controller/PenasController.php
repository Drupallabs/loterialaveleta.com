<?php

namespace Drupal\penas\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class PenasController.
 */
class PenasController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return array(
      //Your theme hook name
      '#theme' => 'penas',
    );
  }

}
