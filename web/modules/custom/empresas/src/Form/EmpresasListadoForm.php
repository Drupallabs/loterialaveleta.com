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

        $options = ['' => '-- Seleccione Una Empresa'];

        $empresas_ids = \Drupal::entityQuery('empresa')->execute();

        foreach ($empresas_ids as $empresa_id) {
            $empresa = Empresa::load($empresa_id);

            $options[$empresa_id] = $empresa->getNombre();
        }

        $form['filtros'] = [
            '#type'  => 'fieldset',
            '#title' => 'Filtros',
            '#open'  => true,
            '#attributes' => array(
                'class' => array('container-inline'),
            ),
        ];
        $form['filtros']['empresa'] = [
            '#type' => 'select',
            '#title' => 'Empresa',
            '#options' => $options,
        ];
        $form['filtros']['numero'] = [
            '#type' => 'textfield',
            '#title' => 'Numero Decimo',
            '#size' => 10
        ];
        $form['filtros']['codigo'] = [
            '#type' => 'textfield',
            '#title' => 'Codigo de TPV',
            '#required' => false,
            '#size' => 10
        ];
        $form['filtros']['email'] = [
            '#type' => 'textfield',
            '#title' => 'Email',
            '#required' => false,
            '#size' => 50
        ];

        $form['filtros']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Filtrar',
        ];
        $form['#method'] = 'get';
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $empresa = $form_state->getValue('empresa');
        if ($empresa == '') {
            $form_state->setErrorByName('empresa', 'Introduce una empresa');
        }

        // parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        //$field = $form_state->getValues();
        //$empresa = $field["empresa"];
        //dump($empresa);
        //$form_state->setValue(['filtros', 'empresa'], $empresa);
        //$numero = $field["numero"];
        //$form_state->set(array('filtros' => 'empresa'), $empresa);
        //parent::submitForm($form, $form_state);
    }
}
