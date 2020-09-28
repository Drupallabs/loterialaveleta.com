<?php

namespace Drupal\sorteos\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\sorteos\Entity\ImporterInterface;
use Drupal\sorteos\Plugin\ImporterInterface as ImporterPluginInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Importer plugins.
 */
abstract class ImporterBase extends PluginBase implements ImporterPluginInterface, ContainerFactoryPluginInterface
{

    use StringTranslationTrait;
    use DependencySerializationTrait;

    /**
     * @var \Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, ClientInterface $httpClient)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->entityTypeManager = $entityTypeManager;
        $this->httpClient = $httpClient;

        if (!isset($configuration['config'])) {
            throw new PluginException('Missing Importer configuration.');
        }

        if (!$configuration['config'] instanceof ImporterInterface) {
            throw new PluginException('Wrong Importer configuration.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('entity_type.manager'),
            $container->get('http_client')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->configuration['config'];
    }
}
