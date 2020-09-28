<?php

namespace Drupal\abonados;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\user\UserInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\abonados\Entity\AbonadoType;

/**
 * Defines a class to build a listing of Abonado entities.
 *
 * @ingroup abonados 
 */
class AbonadoListBuilder extends EntityListBuilder
{
  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new NodeListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter)
  {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
  }


  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
  {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Abonado ID');
    $header['user_id'] = 'ID Usuario';
    $header['user_name'] = 'Nombre Usuario';
    $header['nombre'] = 'Nombre';
    $header['type'] = 'Tipo';
    $header['numero'] = 'Numero Decimo';
    $header['status'] = 'Estado';
    $header['created'] = 'Creado';
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var \Drupal\abonados\Entity\Abonado $entity */
    $abonado_type = AbonadoType::load($entity->bundle());
    $row['id'] = $entity->id();
    $row['user_id'] = $entity->getOwnerId();
    $row['user_name'] = $this->getUserName($entity);
    $row['nombre'] = Link::createFromRoute(
      $entity->label(),
      'entity.abonado.edit_form',
      ['abonado' => $entity->id()]
    );
    $row['type'] = $abonado_type->label();
    $row['numero'] = $entity->getNumero();
    $row['status'] = $entity->getStatus() ? 'Activo' : 'Inactivo';
    $row['created'] = $this->dateFormatter->format($entity->getChangedTime(), 'short');
    return $row + parent::buildRow($entity);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   * @return \Drupal\Core\Link|\Drupal\Core\StringTranslation\TranslatableMarkup
   */
  protected function getUserName(EntityInterface $entity)
  {
    /* @var $entity \Drupal\abonados\Entity\Abonado */
    $user = $entity->getUser();
    if ($user instanceof UserInterface) {
      $username = Link::createFromRoute(
        $user->getDisplayName(),
        'entity.user.canonical',
        ['user' => $user->id()]
      );
    } else {
      $username = $this->t('Anonymous');
    }
    return $username;
  }
}
