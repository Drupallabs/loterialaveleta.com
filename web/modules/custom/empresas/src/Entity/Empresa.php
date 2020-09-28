<?php

namespace Drupal\empresas\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Empresa entity.
 *
 * @ingroup empresas
 *
 * @ContentEntityType(
 *   id = "empresa",
 *   label = @Translation("Empresa"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\empresas\EmpresaListBuilder",
 *     "views_data" = "Drupal\empresas\Entity\EmpresaViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\empresas\Form\EmpresaForm",
 *       "add" = "Drupal\empresas\Form\EmpresaForm",
 *       "edit" = "Drupal\empresas\Form\EmpresaForm",
 *       "delete" = "Drupal\empresas\Form\EmpresaDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\empresas\EmpresaHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\empresas\EmpresaAccessControlHandler",
 *   },
 *   base_table = "empresa",
 *   translatable = FALSE,
 *   admin_permission = "administer empresa entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "nombre",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/laveleta/empresa/{empresa}",
 *     "add-form" = "/admin/laveleta/empresa/add",
 *     "edit-form" = "/admin/laveleta/empresa/{empresa}/edit",
 *     "delete-form" = "/admin/laveleta/empresa/{empresa}/delete",
 *     "collection" = "/admin/laveleta/empresa",
 *   },
 *   field_ui_base_route = "empresa.settings"
 * )
 */
class Empresa extends ContentEntityBase implements EmpresaInterface
{

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
  {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getNombre()
  {
    return $this->get('nombre')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNombre($nombre)
  {
    $this->set('nombre', $nombre);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getContacto()
  {
    return $this->get('contacto')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setContacto($contacto)
  {
    $this->set('contacto', $contacto);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail()
  {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email)
  {
    $this->set('nombre', $email);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTelefono()
  {
    return $this->get('telefono')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTelefono($telefono)
  {
    $this->set('nombre', $telefono);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getContrasena()
  {
    return $this->get('contrasena')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setContrasena($contrasena)
  {
    $this->set('contrasena', $contrasena);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime()
  {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp)
  {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner()
  {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId()
  {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid)
  {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account)
  {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getImagen()
  {
    return $this->get('imagen')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setImagen($imagen)
  {
    $this->set('imagen', $imagen);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Creada por')
      ->setDescription('El usuario que cre la empresa.')
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['nombre'] = BaseFieldDefinition::create('string')
      ->setLabel('Nombre')
      ->setDescription('Nombre de la empresa')
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['contacto'] = BaseFieldDefinition::create('string')
      ->setLabel('Contacto')
      ->setDescription('Persona de Contacto')
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel('Email')
      ->setDescription('Email de Contacto')
      ->setSettings([
        'max_length' => 150
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        // 'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['telefono'] = BaseFieldDefinition::create('string')
      ->setLabel('Telefono')
      ->setDescription('Telefono de contacto')
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['contrasena'] = BaseFieldDefinition::create('string')
      ->setLabel('Contraseña')
      ->setDescription('Introduzca solo letras o numeros, sin espacios ni signos de puntación')
      ->setSettings([
        'max_length' => 10,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription('')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['imagen'] = BaseFieldDefinition::create('image')
      ->setLabel('Imagen Empresa')
      ->setSettings([
        'file_directory' => 'empresas',
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        "max_filesize" => '3000 KB',
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'image',
        'settings' => [
          'image_style' => 'medium'
        ],
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'label' => 'hidden',
        'type' => 'image_image',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }
}
