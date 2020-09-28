<?php

namespace Drupal\abonados\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for abonados routes.
 */
class AbonadosController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
