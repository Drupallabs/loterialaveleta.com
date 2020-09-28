<?php

namespace Drupal\sorteos\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Importer configuration entity.
 */
interface ImporterInterface extends ConfigEntityInterface
{

    /**
     * Returns the configuration specific to the chosen plugin.
     *
     * @return array
     */
    public function getPluginConfiguration();

    /**
     * Returns the Importer plugin ID to be used by this importer.
     *
     * @return string
     */
    public function getPluginId();

    /**
     * Whether or not to update existing sorteos if they have already been imported.
     *
     * @return bool
     */
    public function updateExisting();

    /**
     *
     * @return bool
     */
    public function getParamFecha();

    /**
     *
     * @return bool
     */
    public function getDias();
    
    /**
     * active
     *
     * @return bool
     */
    public function getActive();

    /**
     * Returns the source of the sorteos.
     *
     * @return string
     */
    public function getSource();

    /**
     * Returns the Product type that needs to be created.
     *
     * @return string
     */
    public function getBundle();
}
