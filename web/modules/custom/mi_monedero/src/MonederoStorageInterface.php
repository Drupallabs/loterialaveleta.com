<?php

namespace Drupal\mi_monedero;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\mi_monedero\Entity\MonederoInterface;

/**
 * Defines the storage handler class for Monedero entities.
 *
 * This extends the base storage class, adding required special handling for
 * Monedero entities.
 *
 * @ingroup mi_monedero
 */
interface MonederoStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Monedero revision IDs for a specific Monedero.
   *
   * @param \Drupal\mi_monedero\Entity\MonederoInterface $entity
   *   The Monedero entity.
   *
   * @return int[]
   *   Monedero revision IDs (in ascending order).
   */
  public function revisionIds(MonederoInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Monedero author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Monedero revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
