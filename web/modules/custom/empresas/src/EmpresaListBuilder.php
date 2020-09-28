<?php

namespace Drupal\empresas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Image;

/**
 * Defines a class to build a listing of Empresa entities.
 *
 * @ingroup empresas
 */
class EmpresaListBuilder extends EntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Empresa ID');
    $header['nombre'] = 'Nombre';
    $header['imagen'] = 'Imagen';
    $header['contacto'] = 'Contacto';
    $header['email'] = 'Email';
    $header['telefono'] = 'Teléfono';
    $header['contrasena'] = 'Contraseña';
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    $file = $entity->getImagen();
    $imagen = '';
    /* @var \Drupal\empresas\Entity\Empresa $entity */
    $row['id'] = $entity->id();
    $row['nombre'] = Link::createFromRoute(
      $entity->label(),
      'entity.empresa.edit_form',
      ['empresa' => $entity->id()]
    );
    if (isset($file) && null !== $file->getFileUri()) {
      $thumb_url = \Drupal\image\Entity\ImageStyle::load('thumbnail')->buildUrl($file->getFileUri());
      $imagen = \Drupal\Core\Render\Markup::create("<img src='$thumb_url'>");
    }
    $row['imagen'] = $imagen;

    $row['contacto'] = $entity->getContacto();
    $row['email'] = $entity->getEmail();
    $row['telefono'] = $entity->getTelefono();
    $row['contrasena'] = $entity->getContrasena();
    return $row + parent::buildRow($entity);
  }
}
