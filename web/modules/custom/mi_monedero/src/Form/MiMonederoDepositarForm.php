<?php

namespace Drupal\mi_monedero\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class MiMonederoDepositarForm extends FormBase
{

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityManager;

    /**
     * The config factory
     *
     * @var Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $factory;

    /**
     * Class constructor.
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $factory)
    {
        $this->entityTypeManager = $entity_type_manager;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity_type.manager'),
            $container->get('config.factory')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'mi_monedero_depositar';
    }



    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {


        $currencies = $this->entityTypeManager->getStorage('commerce_currency')->loadMultiple();
        $currencyCodes = [];
        foreach ($currencies as $currency) {
            $currency_code = $currency->getCurrencyCode();
            $currencyCodes[$currency_code] = $currency_code;
        }


        $form['cantidad'] = [
            '#type' => 'number',
            '#min' => 0.0,
            '#title' => 'Cantidad',
            '#description' => 'Por favor, introduce la cantidad que quieres depositar. Mínimo 10 Euros. ',
            '#step' => 0.01,
            '#default_value' => 10,
            '#size' => 30,
            '#maxlength' => 128,
            '#required' => TRUE,
        ];

        $form['currency'] = [
            '#type' => 'select',
            '#title' => 'Selecciona la Moneda',
            '#description' => '',
            '#options' => $currencyCodes,
        ];

        $form['actions'] = ['#type' => 'actions'];
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Siguiente',
        ];

        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $config = $this->factory->get('commerce_redsys.configuracion');
        if ($form_state->getValue('cantidad') < 10) {
            $form_state->setErrorByName('cantidad', $this->t('La cantidad debe ser mayor de 10 euros.'));
        } else {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cantidad = $form_state->getValue('cantidad');
        $tempstore = \Drupal::service('user.private_tempstore')->get('mi_monedero');
        $tempstore->set('cantidad', $cantidad);
        $path = URL::fromUserInput('/mi_monedero/tpv-virtual', array('query' => array('cantidad' => $cantidad)))->toString();
        $response = new RedirectResponse($path);
        $response->send();
    }
}
