<?php

namespace Drupal\empresas;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Empresa entity.
 *
 * @see \Drupal\empresas\Entity\Empresa.
 */
class EmpresaAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\empresas\Entity\EmpresaInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished empresa entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published empresa entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit empresa entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete empresa entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add empresa entities');
  }


}
