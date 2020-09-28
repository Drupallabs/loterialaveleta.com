<?php

namespace Drupal\empresas\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Empresa entities.
 */
class EmpresaViewsData extends EntityViewsData {

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
