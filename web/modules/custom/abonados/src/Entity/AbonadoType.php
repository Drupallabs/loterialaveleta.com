<?php

namespace Drupal\abonados\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Abonado type entity.
 *
 * @ConfigEntityType(
 *   id = "abonado_type",
 *   label = @Translation("Abonado type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\abonados\AbonadoTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\abonados\Form\AbonadoTypeForm",
 *       "edit" = "Drupal\abonados\Form\AbonadoTypeForm",
 *       "delete" = "Drupal\abonados\Form\AbonadoTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\abonados\AbonadoTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "abonado_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "abonado",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/laveleta/abonado_type/{abonado_type}",
 *     "add-form" = "/admin/laveleta/abonado_type/add",
 *     "edit-form" = "/admin/laveleta/abonado_type/{abonado_type}/edit",
 *     "delete-form" = "/admin/laveleta/abonado_type/{abonado_type}/delete",
 *     "collection" = "/admin/laveleta/abonado_type"
 *   }
 * )
 */
class AbonadoType extends ConfigEntityBundleBase implements AbonadoTypeInterface {

  /**
   * The Abonado type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Abonado type label.
   *
   * @var string
   */
  protected $label;

}
