<?php

namespace Drupal\sorteos\Access;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\sorteos\Entity\SorteoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Access controller for the Sorteo entity type.
 */
class SorteoAccessControlHandler extends EntityAccessControlHandler implements EntityHandlerInterface
{

    /**
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * SorteoAccessControlHandler constructor.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     */
    public function __construct(EntityTypeInterface $entity_type, EntityTypeManagerInterface $entityTypeManager)
    {
        parent::__construct($entity_type);
        $this->entityTypeManager = $entityTypeManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('entity_type.manager')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {

        /** @var SorteoInterface $entity */
        switch ($operation) {
            case 'view':
                return AccessResult::allowedIfHasPermission($account, 'view sorteo entity');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit sorteo entity');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete sorteo entity');
        }

        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
    {
        return AccessResult::allowedIfHasPermission($account, 'add sorteo entities');
    }
}
