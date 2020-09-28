<?php

namespace Drupal\abonados\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Abonado entities.
 *
 * @ingroup abonados
 */
interface AbonadoInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface
{

  /**
   * Add get/set methods for your configuration properties here.
   */


  public function getNombre();
  public function setNombre($nombre);
  public function getNumero();
  public function setNumero($numero);

  /**
   * Gets the Abonado creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Abonado.
   */
  public function getCreatedTime();

  /**
   * Sets the Abonado creation timestamp.
   *
   * @param int $timestamp
   *   The Abonado creation timestamp.
   *
   * @return \Drupal\abonados\Entity\AbonadoInterface
   *   The called Abonado entity.
   */
  public function setCreatedTime($timestamp);
}
