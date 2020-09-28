<?php

namespace Drupal\jugar\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller routines for jugar routes.
 */
class JugarController {
  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'jugar';
  }

}
