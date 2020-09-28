<?php

namespace Drupal\mi_monedero;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Monedero entities.
 *
 * @ingroup mi_monedero
 */
class MonederoListBuilder extends EntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Monedero ID');
    $header['name'] = $this->t('Name');
    $header['user'] = 'Propietario';
    $header['cantidad'] = 'Cantidad';
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var \Drupal\mi_monedero\Entity\Monedero $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.monedero.edit_form',
      ['monedero' => $entity->id()]
    );
    $row['user'] = $entity->getOwner()->getUsername();
    $row['cantidad'] = $entity->getCantidad() . '€';
    return $row + parent::buildRow($entity);
  }
}
