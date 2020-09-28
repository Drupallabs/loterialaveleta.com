<?php

namespace Drupal\sorteos\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Url;

/**
 * Defines the Importer entity. ESTO ES UN configuration entity
 *
 * @ConfigEntityType(
 *   id = "importer",
 *   label = @Translation("Importer"),
 *   handlers = {
 *     "list_builder" = "Drupal\sorteos\ImporterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\sorteos\Form\ImporterForm",
 *       "edit" = "Drupal\sorteos\Form\ImporterForm",
 *       "delete" = "Drupal\sorteos\Form\ImporterDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "importer",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/laveleta/importer/add",
 *     "edit-form" = "/admin/lavleta/importer/{importer}/edit",
 *     "delete-form" = "/admin/laveleta/importer/{importer}/delete",
 *     "collection" = "/admin/laveleta/importer"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "plugin",
 *     "param_fecha",
 *     "dias",
 *     "update_existing",
 *     "active",
 *     "source",
 *     "bundle",
 *     "plugin_configuration"
 *   }
 * )
 */
class Importer extends ConfigEntityBase implements ImporterInterface
{

    /**
     * The Importer ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The Importer label.
     *
     * @var string
     */
    protected $label;

    /**
     * The plugin ID of the plugin to be used for processing this import.
     *
     * @var string
     */
    protected $plugin;

    /**
     * Le añadimos el parametro fecha a la petición
     *
     * @var bool
     */
    protected $param_fecha = TRUE;

    /**
     * Dias a partir de hoy en el parametro fecha
     *
     * @var integer
     */
    protected $dias;


    /**
     * Whether or not to update existing sorteos if they have already been imported.
     *
     * @var bool
     */
    protected $update_existing = TRUE;


    /**
     * is active the importerd
     *
     * @var bool
     */
    protected $active = TRUE;

    /**
     * The source of the sorteos.
     *
     * @var string
     */
    protected $source;

    /**
     * The sorteo bundle.
     *
     * @var string
     */
    protected $bundle;

    /**
     * The configuration specific to the plugin.
     *
     * @var array
     */
    protected $plugin_configuration;

    /**
     * {@inheritdoc}
     */
    public function getPluginId()
    {
        return $this->plugin;
    }

    /**
     * {@inheritdoc}
     */
    public function updateExisting()
    {
        return $this->update_existing;
    }

    /**
     * {@inheritdoc}
     */
    public function getParamFecha()
    {
        return $this->param_fecha;
    }

    public function getDias()
    {
        return $this->dias;
    }
    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * {@inheritdoc}
     */
    public function getPluginConfiguration()
    {
        return $this->plugin_configuration;
    }
}
