<?php

namespace Drupal\abonados\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Abonado entity.
 *
 * @ingroup abonados
 *
 * @ContentEntityType(
 *   id = "abonado",
 *   label = @Translation("Abonado"),
 *   bundle_label = @Translation("Abonado type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\abonados\AbonadoListBuilder",
 *     "views_data" = "Drupal\abonados\Entity\AbonadoViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\abonados\Form\AbonadoForm",
 *       "add" = "Drupal\abonados\Form\AbonadoForm",
 *       "edit" = "Drupal\abonados\Form\AbonadoForm",
 *       "delete" = "Drupal\abonados\Form\AbonadoDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\abonados\AbonadoHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\abonados\AbonadoAccessControlHandler",
 *   },
 *   base_table = "abonado",
 *   translatable = FALSE,
 *   admin_permission = "administer abonado entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "nombre",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/laveleta/abonado/{abonado}",
 *     "add-page" = "/admin/laveleta/abonado/add",
 *     "add-form" = "/admin/laveleta/abonado/add/{abonado_type}",
 *     "edit-form" = "/admin/laveleta/abonado/{abonado}/edit",
 *     "delete-form" = "/admin/laveleta/abonado/{abonado}/delete",
 *     "collection" = "/admin/laveleta/abonado",
 *   },
 *   bundle_entity_type = "abonado_type",
 *   field_ui_base_route = "entity.abonado_type.edit_form"
 * )
 */
class Abonado extends ContentEntityBase implements AbonadoInterface
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

  public function getNumero()
  {
    return $this->get('numero')->value;
  }

  public function setNumero($numero)
  {
    $this->set('numero', $numero);
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
  public function getUser()
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
  public function esActivo()
  {
    return (bool) $this->getEntityKey('status');
  }
  public function getStatus()
  {
    return (bool) $this->get('status')->value;
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
      ->setLabel('Abonado')
      ->setDescription('El Id del abonado.')
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
          'placeholder' => 'Empieze a escribir el nombre de usuario',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['nombre'] = BaseFieldDefinition::create('string')
      ->setLabel('Nombre')
      ->setDescription('')
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

    $fields['numero'] = BaseFieldDefinition::create('string')
      ->setLabel('Numero Decimo')
      ->setDescription('')
      ->setSettings([
        'max_length' => 5,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription('Indica si el abonado esta activo.')
      ->setLabel('Activo')
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

    return $fields;
  }
}
