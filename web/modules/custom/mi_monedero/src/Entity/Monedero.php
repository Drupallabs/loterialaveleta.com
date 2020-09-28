<?php

namespace Drupal\mi_monedero\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\commerce_price\Entity\CurrencyInterface;

/**
 * Defines the Monedero entity.
 *
 * @ingroup mi_monedero
 *
 * @ContentEntityType(
 *   id = "monedero",
 *   label = @Translation("Monedero"),
 *   handlers = {
 *     "storage" = "Drupal\mi_monedero\MonederoStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\mi_monedero\MonederoListBuilder",
 *     "views_data" = "Drupal\mi_monedero\Entity\MonederoViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\mi_monedero\Form\MonederoForm",
 *       "add" = "Drupal\mi_monedero\Form\MonederoForm",
 *       "edit" = "Drupal\mi_monedero\Form\MonederoForm",
 *       "delete" = "Drupal\mi_monedero\Form\MonederoDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\mi_monedero\MonederoHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\mi_monedero\MonederoAccessControlHandler",
 *   },
 *   base_table = "monedero",
 *   revision_table = "monedero_revision",
 *   revision_data_table = "monedero_field_revision",
 *   translatable = FALSE,
 *   admin_permission = "administer monedero entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/laveleta/monedero/{monedero}",
 *     "add-form" = "/admin/laveleta/monedero/add",
 *     "edit-form" = "/admin/laveleta/monedero/{monedero}/edit",
 *     "delete-form" = "/admin/laveleta/monedero/{monedero}/delete",
 *     "version-history" = "/admin/laveleta/monedero/{monedero}/revisions",
 *     "revision" = "/admin/laveleta/monedero/{monedero}/revisions/{monedero_revision}/view",
 *     "revision_revert" = "/admin/laveleta/monedero/{monedero}/revisions/{monedero_revision}/revert",
 *     "revision_delete" = "/admin/laveleta/monedero/{monedero}/revisions/{monedero_revision}/delete",
 *     "collection" = "/admin/laveleta/monedero",
 *   },
 *   field_ui_base_route = "monedero.settings"
 * )
 */
class Monedero extends EditorialContentEntityBase implements MonederoInterface
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
  protected function urlRouteParameters($rel)
  {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    } elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage)
  {
    if ($this->isNew()) {
      $uid = $this->getOwnerId(); // Comprovamos que ya no tenga un monendero
      $mo = $storage->loadByProperties(['user_id' => $uid]);
      if ($mo) { // Tiene monedero este usuario


        \Drupal::messenger()->addError('El usuario ya tiene un monedero, no se puede crear otro.');
        $response = new RedirectResponse('/admin/laveleta/monedero');
        $response->send();
        throw new \Exception('El usuario ya tiene un monedero, no de puede crear otro.');
      }
    } else {

      parent::preSave($storage);
      // If no revision author has been set explicitly,
      // make the monedero owner the revision author.
      if (!$this->getRevisionUser()) {
        $this->setRevisionUserId($this->getOwnerId());
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name)
  {
    $this->set('name', $name);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getCantidad()
  {
    return $this->get('cantidad')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCantidad($cantidad)
  {
    $this->set('cantidad', $cantidad);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getCurrency()
  {
    return $this->get('currency')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrency(CurrencyInterface $currency)
  {
    $this->set('currency', $currency->getCurrencyCode());
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Monedero entity.'))
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Monedero entity.'))
      ->setRevisionable(TRUE)
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

    $fields['cantidad'] = BaseFieldDefinition::create('decimal')
      ->setLabel('Cantidad')
      ->setDescription('Introduzca la Cantidad del monedero en euros.')
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'decimal',
        'weight' => 1,
      ])
      ->setSetting('display_description', TRUE);

    $fields['currency'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Currency'))
      ->setDescription(t('The currency of the transaction.'))
      ->setCardinality(1)
      ->setRequired(TRUE)
      ->setSetting('target_type', 'commerce_currency')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 2,
      ]);

    $fields['status']->setDescription(t('A boolean indicating whether the Monedero is published.'))
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
