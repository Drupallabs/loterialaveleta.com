<?php

namespace Drupal\empresas\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Empresa entities.
 *
 * @ingroup empresas
 */
interface EmpresaInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface
{

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Empresa nombre.
   *
   * @return string
   *   Nombre of the Empresa.
   */
  public function getNombre();

  /**
   * Sets the Empresa nombre.
   *
   * @param string $nombre
   *   
   * @return \Drupal\empresas\Entity\EmpresaInterface
   *   The called Empresa entity.
   */
  public function setNombre($nombre);

  /**
   * @return string
   */
  public function getContacto();

  /**
   *
   * @param string $contacto
   *   
   * @return \Drupal\empresas\Entity\EmpresaInterface
   */
  public function setContacto($contacto);

  /**
   * @return string
   */
  public function getEmail();

  /**
   *
   * @param string $email
   *   
   * @return \Drupal\empresas\Entity\EmpresaInterface
   */
  public function setEmail($email);

  /**
   * @return string
   */
  public function getTelefono();

  /**
   *
   * @param string $telefono
   *   
   * @return \Drupal\empresas\Entity\EmpresaInterface
   */
  public function setTelefono($telefono);

  /**
   * @return string
   */
  public function getContrasena();

  /**
   *
   * @param string $contrasena
   *   
   * @return \Drupal\empresas\Entity\EmpresaInterface
   */
  public function setContrasena($contrasena);

  /**
   * Gets the Empresa creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Empresa.
   */
  public function getCreatedTime();

  /**
   * Sets the Empresa creation timestamp.
   *
   * @param int $timestamp
   *   The Empresa creation timestamp.
   *
   * @return \Drupal\empresas\Entity\EmpresaInterface
   *   The called Empresa entity.
   */
  public function setCreatedTime($timestamp);
}
