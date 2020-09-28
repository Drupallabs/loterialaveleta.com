<?php

namespace Drupal\mi_monedero\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Monedero entities.
 */
class MonederoViewsData extends EntityViewsData {

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
