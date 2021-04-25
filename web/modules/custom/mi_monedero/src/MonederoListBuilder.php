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
    $header['user_id'] = 'Codigo Usuario';
    $header['cantidad'] = 'Cantidad';
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {

    /* @var \Drupal\mi_monedero\Entity\Monedero $entity */
    if ($entity->getOwner()) {
      $row['id'] = $entity->id();
      $row['name'] = Link::createFromRoute(
        $entity->label(),
        'entity.monedero.edit_form',
        ['monedero' => $entity->id()]
      );
      $row['user'] = Link::createFromRoute(
        $entity->getOwner()->getUsername(),
        'entity.user.edit_form',
        ['user' => $entity->id()]
      );

      $row['user_id'] = $entity->getOwner()->id();
      $row['cantidad'] = $entity->getCantidad() . '€';
    } else {

      $row['id'] = $entity->id();
      $row['name'] = '';
      $row['user'] = '';
      $row['user_id'] = '';
      $row['cantidad'] = '';
    }



    return $row + parent::buildRow($entity);
  }
}
