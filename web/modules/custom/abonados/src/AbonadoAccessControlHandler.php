<?php

namespace Drupal\abonados;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Abonado entity.
 *
 * @see \Drupal\abonados\Entity\Abonado.
 */
class AbonadoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\abonados\Entity\AbonadoInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished abonado entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published abonado entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit abonado entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete abonado entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add abonado entities');
  }


}
