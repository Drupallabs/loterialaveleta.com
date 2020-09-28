<?php

namespace Drupal\mi_monedero;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class MonederoStorage extends SqlContentEntityStorage implements MonederoStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(MonederoInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {monedero_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {monedero_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
