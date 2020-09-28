<?php

namespace Drupal\mi_monedero\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Monedero entities.
 *
 * @ingroup mi_monedero
 */
interface MonederoInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface
{

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Monedero name.
   *
   * @return string
   *   Name of the Monedero.
   */
  public function getName();

  /**
   * Sets the Monedero name.
   *
   * @param string $name
   *   The Monedero name.
   *
   * @return \Drupal\mi_monedero\Entity\MonederoInterface
   *   The called Monedero entity.
   */
  public function setCantidad($cantidad);

  /**
   * Gets the Monedero cantidad.
   *
   * @return string
   *   Cantidad of the Monedero.
   */
  public function getCantidad();

  /**
   * Sets the Monedero name.
   *
   * @param string $name
   *   The Monedero name.
   *
   * @return \Drupal\mi_monedero\Entity\MonederoInterface
   *   The called Monedero entity.
   */
  public function setName($name);
  /**
   * Gets the Monedero creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Monedero.
   */
  public function getCreatedTime();

  /**
   * Sets the Monedero creation timestamp.
   *
   * @param int $timestamp
   *   The Monedero creation timestamp.
   *
   * @return \Drupal\mi_monedero\Entity\MonederoInterface
   *   The called Monedero entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Monedero revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Monedero revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\mi_monedero\Entity\MonederoInterface
   *   The called Monedero entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Monedero revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Monedero revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\mi_monedero\Entity\MonederoInterface
   *   The called Monedero entity.
   */
  public function setRevisionUserId($uid);
}
