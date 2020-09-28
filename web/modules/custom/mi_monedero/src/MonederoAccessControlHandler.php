<?php

namespace Drupal\mi_monedero;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Monedero entity.
 *
 * @see \Drupal\mi_monedero\Entity\Monedero.
 */
class MonederoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\mi_monedero\Entity\MonederoInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished monedero entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published monedero entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit monedero entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete monedero entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add monedero entities');
  }


}
