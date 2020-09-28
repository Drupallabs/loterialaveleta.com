<?php

namespace Drupal\sorteos\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Sorteo type configuration entity type.
 *
 * @ConfigEntityType(
 *   id = "sorteo_type",
 *   label = @Translation("Tipo de Sorteo"),
 *   handlers = {
 *     "list_builder" = "Drupal\sorteos\SorteoTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\sorteos\Form\SorteoTypeForm",
 *       "edit" = "Drupal\sorteos\Form\SorteoTypeForm",
 *       "delete" = "Drupal\sorteos\Form\SorteoTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "sorteo_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "sorteo",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/laveleta/sorteo_type/{sorteo_type}",
 *     "add-form" = "/admin/laveleta/sorteo_type/add",
 *     "edit-form" = "/admin/laveleta/sorteo_type/{sorteo_type}/edit",
 *     "delete-form" = "/admin/laveleta/sorteo_type/{sorteo_type}/delete",
 *     "collection" = "/admin/laveleta/sorteo_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   }
 * )
 */
class SorteoType extends ConfigEntityBundleBase implements SorteoTypeInterface
{

    /**
     * The Sorteo type ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The Sorteo type label.
     *
     * @var string
     */
    protected $label;
}
