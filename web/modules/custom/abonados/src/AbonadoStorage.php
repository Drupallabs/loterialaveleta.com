<?php

namespace Drupal\abonados;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The subscription storage.
 */
class AbonadoStorage extends SqlContentEntityStorage implements AbonadoStorageInterface
{

    /**
     * The event dispatcher.
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Constructs a new EntityActivityContentEntityStorage object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition.
     * @param \Drupal\Core\Database\Connection $database
     *   The database connection to be used.
     * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
     *   The entity field manager.
     * @param \Drupal\Core\Cache\CacheBackendInterface $cache
     *   The cache backend to be used.
     * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
     *   The language manager.
     * @param \Drupal\Core\Cache\MemoryCache\MemoryCacheInterface $memory_cache
     *   The memory cache.
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
     *   The event dispatcher.
     */
    public function __construct(EntityTypeInterface $entity_type, Connection $database, EntityFieldManagerInterface $entity_field_manager, CacheBackendInterface $cache, LanguageManagerInterface $language_manager, MemoryCacheInterface $memory_cache, EventDispatcherInterface $event_dispatcher)
    {
        parent::__construct($entity_type, $database, $entity_field_manager, $cache, $language_manager, $memory_cache);
        $this->eventDispatcher = $event_dispatcher;
        // @TODO Dispatch events related to hooks for a Abonado.
    }

    /**
     * {@inheritdoc}
     */
    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('database'),
            $container->get('entity_field.manager'),
            $container->get('cache.entity'),
            $container->get('language_manager'),
            $container->get('entity.memory_cache'),
            $container->get('event_dispatcher')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadMultipleByProduct(ProductInterface $product, $active = TRUE)
    {
        $properties = [
            'product_id' => $product->id(),
        ];
        if ($active) {
            $properties['status'] = TRUE;
        }
        return $this->loadByProperties($properties);
    }


    /**
     * {@inheritdoc}
     */
    public function loadMultipleByUser(AccountInterface $account, $active = TRUE)
    {
        $properties = [
            'uid' => $account->id(),
        ];
        if ($active) {
            $properties['status'] = TRUE;
        }
        return $this->loadByProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function loadMultipleByMail($mail, $active = TRUE)
    {;
        $properties = [
            'mail' => $mail,
        ];
        if ($active) {
            $properties['status'] = TRUE;
        }
        return $this->loadByProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function loadMultipleByProductAndMail(ProductInterface $product, $mail)
    {;
        $properties = [
            'product_id' => $product->id(),
            'mail' => $mail,
        ];
        return $this->loadByProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getAbonadoFor(ProductInterface $product, $mail)
    {;
        $abonados = $this->loadMultipleByProductAndMail($product, $mail);
        return !empty($abonados) ? reset($abonados) : NULL;
    }
}
