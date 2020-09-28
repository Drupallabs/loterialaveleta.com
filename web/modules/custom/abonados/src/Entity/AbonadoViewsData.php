<?php

namespace Drupal\abonados\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Abonado entities.
 */
class AbonadoViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
