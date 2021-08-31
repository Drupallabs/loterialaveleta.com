<?php

namespace Drupal\sorteos\Commands;

use Drupal\Core\Lock\LockBackendInterface;
use Drupal\sorteos\Plugin\ImporterManager;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;
use Drupal\Core\State\State;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SorteoCommands extends DrushCommands
{

    /**
     * @var \Drupal\sorteos\Plugin\ImporterManager
     */
    protected $importerManager;

    /**
     * @var \Drupal\Core\Lock\LockBackendInterface
     */
    protected $lock;

    /**
     * The logger. Conflict with $logger of Drush, 
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $loggerSorteos;

    /**
     * The state
     * @var \Drupal\Core\State\State
     */
    protected $state;

    /**
     * SorteoCommands constructor.
     *
     * @param \Drupal\sorteos\Plugin\ImporterManager $importerManager
     * @param \Drupal\Core\Lock\LockBackendInterface $lock
     * @param \Psr\Log\LoggerInterface
     * @param \Drupal\Core\State\State
     */
    public function __construct(ImporterManager $importerManager, LockBackendInterface $lock, LoggerInterface $loggerSorteos, State $state)
    {
        $this->importerManager = $importerManager;
        $this->lock = $lock;
        $this->loggerSorteos = $loggerSorteos; // Conflict with $this->logger of DrushCommand
        $this->state = $state;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('sorteos.importer_manager'),
            $container->get('lock'),
            $container->get('sorteos.logger.channel.sorteos'),
            $container->get('state')
        );
    }

    /**
     * Imports the Sorteos
     *
     * @option importer
     *   The importer config ID to use.
     *
     * @command sorteos-import-run
     * @aliases sir
     *
     * @param array $options
     *   The command options.
     */
    public function import($options = ['importer' => InputOption::VALUE_OPTIONAL])
    {
        $this->state->set('sorteos.last_import', \Drupal::time()->getRequestTime());

        $importer = $options['importer'];
        // llega un parametro solo creamos un plugin
        if (!is_null($importer)) {
            $plugin = $this->importerManager->createInstanceFromConfig($importer);
            if (is_null($plugin)) {
                $this->logger()->log('error', t('The specified importer does not exist.'));
                return;
            }

            $this->runPluginImport($plugin);
            return;
        }
        // no hay parametro creamos todos los plugins
        $plugins = $this->importerManager->createInstanceFromAllConfigs();
        if (!$plugins) {
            $this->logger()->log('error', t('There are no importers to run.'));
            return;
        }

        foreach ($plugins as $plugin) {
            $this->runPluginImport($plugin);
        }
    }

    /**
     * Runs an individual Importer plugin.
     *
     * @param \Drupal\sorteos\Plugin\ImporterInterface $plugin
     */
    protected function runPluginImport(\Drupal\sorteos\Plugin\ImporterInterface $plugin)
    {
        $this->logger()->log('status', t('run PluginImport'));

        if (!$this->lock->acquire($plugin->getPluginId())) {
            $this->logger()->log('notice', t('The plugin @plugin is already running. Waiting for it to finish.', ['@plugin' => $plugin->getPluginDefinition()['label']]));
            if ($this->lock->wait($plugin->getPluginId())) {
                $this->logger()->log('notice', t('The wait is killing me. Giving up.'));
                return;
            }
        }
        $result = $plugin->import();
        $message_values = ['@importer' => $plugin->getConfig()->label()];
        if ($result) {
            $this->logger()->log('notice', t('The "@importer" importer has been run.', $message_values));
            $this->lock->release($plugin->getPluginId());
            return;
        }

        $this->logger()->log('error', t('There was a problem running the "@importer" importer.', $message_values));
    }
}
