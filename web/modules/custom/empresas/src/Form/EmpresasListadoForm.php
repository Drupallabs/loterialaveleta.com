<?php

namespace Drupal\empresas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\empresas\Entity\Empresa;

class EmpresasListadoForm extends FormBase
{
    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager)
    {
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity_type.manager')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'empresas_listado';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        //$form['#action'] = '';
        $options = ['' => '-- Seleccione Una Empresa'];

        $empresas_ids = \Drupal::entityQuery('empresa')->execute();

        foreach ($empresas_ids as $empresa_id) {
            $empresa = Empresa::load($empresa_id);

            $options[$empresa_id] = $empresa->getNombre();
        }
        $form['filtros']['empresa'] = [
            '#type' => 'select',
            '#title' => 'Empresa',
            '#options' => $options,
        ];

        $form['filtros']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Filtrar',
        ];
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $empresa = $form_state->getValue('empresa');

        if ($empresa == '') {
            $form_state->setErrorByName('codigo', 'Introduce una empresa');
        }

        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }
}
