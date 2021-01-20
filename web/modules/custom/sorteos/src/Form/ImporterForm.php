<?php

namespace Drupal\sorteos\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\sorteos\Plugin\ImporterManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for creating/editing Importer entities.
 */
class ImporterForm extends EntityForm
{

    /**
     * @var \Drupal\sorteos\Plugin\ImporterManager
     */
    protected $importerManager;

    /**
     * ImporterForm constructor.
     *
     * @param \Drupal\sorteos\Plugin\ImporterManager $importerManager
     * @param \Drupal\Core\Messenger\MessengerInterface $messenger
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     */
    public function __construct(ImporterManager $importerManager, MessengerInterface $messenger, EntityTypeManagerInterface $entityTypeManager)
    {
        $this->importerManager = $importerManager;
        $this->messenger = $messenger;
        $this->entityTypeManager = $entityTypeManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('sorteos.importer_manager'),
            $container->get('messenger'),
            $container->get('entity_type.manager')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state)
    {
        $form = parent::form($form, $form_state);

        /** @var \Drupal\sorteos\Entity\Importer $importer */
        $importer = $this->entity;

        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#maxlength' => 255,
            '#default_value' => $importer->label(),
            '#description' => $this->t('Name of the Importer.'),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $importer->id(),
            '#machine_name' => [
                'exists' => '\Drupal\sorteos\Entity\Importer::load',
            ],
            '#disabled' => !$importer->isNew(),
        ];

        $definitions = $this->importerManager->getDefinitions();
        $options = [];
        foreach ($definitions as $id => $definition) {
            $options[$id] = $definition['label'];
        }

        $form['plugin'] = [
            '#type' => 'select',
            '#title' => $this->t('Plugin'),
            '#default_value' => $importer->getPluginId(),
            '#options' => $options,
            '#description' => $this->t('The plugin to be used with this importer.'),
            '#required' => TRUE,
            '#empty_option' => $this->t('Please select a plugin'),
            '#ajax' => array(
                'callback' => [$this, 'pluginConfigAjaxCallback'],
                'wrapper' => 'plugin-configuration-wrapper'
            ),
        ];

        $form['plugin_configuration'] = [
            '#type' => 'hidden',
            '#prefix' => '<div id="plugin-configuration-wrapper">',
            '#suffix' => '</div>',
        ];

        $plugin_id = NULL;
        if ($importer->getPluginId()) {
            $plugin_id = $importer->getPluginId();
        }
        if ($form_state->getValue('plugin') && $plugin_id !== $form_state->getValue('plugin')) {
            $plugin_id = $form_state->getValue('plugin');
        }

        if ($plugin_id) {
            /** @var \Drupal\sorteos\Plugin\ImporterInterface $plugin */
            $plugin = $this->importerManager->createInstance($plugin_id, ['config' => $importer]);
            $form['plugin_configuration']['#type'] = 'details';
            $form['plugin_configuration']['#tree'] = TRUE;
            $form['plugin_configuration']['#open'] = TRUE;
            $form['plugin_configuration']['#title'] = $this->t('Plugin configuration for <em>@plugin</em>', ['@plugin' => $plugin->getPluginDefinition()['label']]);
            $form['plugin_configuration']['plugin'] = $plugin->getConfigurationForm($importer);
        }


        $form['update_existing'] = [
            '#type' => 'checkbox',
            '#title' => 'Actualizar existentes',
            '#description' => $this->t('Actuliza los sorteos que ya esten importados.'),
            '#default_value' => $importer->updateExisting(),
        ];

        $form['param_fecha'] = [
            '#type' => 'checkbox',
            '#title' => 'Parametro Fecha',
            '#description' => 'Añade el parametro fecha a la url &fecha=yyyymmdd',
            '#default_value' => $importer->getParamFecha(),
        ];
                
        $form['dias'] = [
            '#type' => 'number',
            '#title' => 'Dias',
            '#description' => 'Dias a partir de hoy en los que coger el sorteo en el parametro fecha, por defecto + 6, si se pone 0 es Hoy.',
            '#default_value' => $importer->getDias(),
        ];

        $form['active'] = [
            '#type' => 'checkbox',
            '#title' => 'Activo',
            '#default_value' => $importer->getActive(),
        ];


        $form['bundle'] = [
            '#type' => 'entity_autocomplete',
            '#target_type' => 'sorteo_type',
            '#title' => $this->t('Sorteo type'),
            '#default_value' => $importer->getBundle() ? $this->entityTypeManager->getStorage('sorteo_type')->load($importer->getBundle()) : NULL,
            '#description' => $this->t('The type of sorteos that need to be created.'),
            '#required' => TRUE,
        ];


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        /** @var \Drupal\sorteos\Entity\Importer $importer */
        $importer = $this->entity;
        $importer->set('plugin_configuration', $importer->getPluginConfiguration()['plugin']);
        $status = $importer->save();

        switch ($status) {
            case SAVED_NEW:
                $this->messenger->addMessage($this->t('Created the %label Importer.', [
                    '%label' => $importer->label(),
                ]));
                break;

            default:
                $this->messenger->addMessage($this->t('Saved the %label Importer.', [
                    '%label' => $importer->label(),
                ]));
        }

        $form_state->setRedirectUrl($importer->toUrl('collection'));
    }

    /**
     * Ajax callback for the plugin configuration form elements.
     *
     * @param $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *
     * @return array
     */
    public function pluginConfigAjaxCallback($form, FormStateInterface $form_state)
    {
        return $form['plugin_configuration'];
    }
}
