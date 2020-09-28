<?php

namespace Drupal\abonados\Form;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_product_reminder\HelperServiceInterface;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Flood\FloodInterface;

class AbonarseForm extends FormBase
{

    /**
     * Drupal\Core\Config\ConfigFactoryInterface definition.
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * Drupal\Core\Entity\EntityTypeManagerInterface definition.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * Drupal\commerce_product_reminder\HelperServiceInterface definition.
     *
     * @var \Drupal\commerce_product_reminder\HelperServiceInterface
     */
    protected $helper;

    /**
     * Drupal\Component\Utility\EmailValidatorInterface definition.
     *
     * @var \Drupal\Component\Utility\EmailValidatorInterface
     */
    protected $emailValidator;

    /**
     * Drupal\Core\Flood\FloodInterface definition.
     *
     * @var \Drupal\Core\Flood\FloodInterface
     */
    protected $flood;

    /**
     * Drupal\Core\Render\RendererInterface definition.
     *
     * @var \Drupal\Core\Render\RendererInterface
     */
    protected $renderer;

    /**
     * Drupal\commerce\CommerceContentEntityStorage definition.
     *
     * @var \Drupal\commerce\CommerceContentEntityStorage
     */
    protected $productStorage;

    /**
     * ReminderSubscriptionForm constructor.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     * @param \Drupal\commerce_product_reminder\HelperServiceInterface $helper
     * @param \Drupal\Component\Utility\EmailValidatorInterface $email_validator
     * @param \Drupal\Core\Flood\FloodInterface $flood
     * @param \Drupal\Core\Render\RendererInterface $renderer
     *
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
     */
    public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, HelperServiceInterface $helper, EmailValidatorInterface $email_validator, FloodInterface $flood, RendererInterface $renderer)
    {
        $this->configFactory = $config_factory;
        $this->entityTypeManager = $entity_type_manager;
        $this->helper = $helper;
        $this->emailValidator = $email_validator;
        $this->flood = $flood;
        $this->productStorage = $entity_type_manager->getStorage('commerce_product');
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('config.factory'),
            $container->get('entity_type.manager'),
            $container->get('commerce_product_reminder.helper'),
            $container->get('email.validator'),
            $container->get('flood'),
            $container->get('renderer')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'abonarse_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $product_id = NULL)
    {
        if (!$product_id) {
            return [];
        }
        $form['product_id'] = [
            '#type' => 'value',
            '#value' => $product_id,
        ];

        $form['abonarse'] = [
            '#type' => 'checkbox',
            '#title' => 'Abonarse a este numero?',
            '#required' => true
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'ABONARSE',
            '#button_type' => '',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        $product_id = $values['product_id'];
        $product = $this->productStorage->load($product_id);
        if (!$product instanceof ProductInterface) {
            $form_state->setError($form, $this->t('Ha ocurrido un error'));
        }

        if (empty($values['abonarse'])) {
            $form_state->setError($form, $this->t('Debes Seleccionar la casilla de abonarse'));
        }
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $values = $form_state->getValues();
        $status_message = [
            '#type' => 'processed_text',
            '#text' => 'Gracias por abonarte a este numero',
            //'#format' => $config->get('form.status_message.format') ?: filter_fallback_format(),
        ];
        $status_message = $this->renderer->renderRoot($status_message);
        // necesitado el producto ?
        $product_id = $values['product_id'];
        if (!empty($product_id)) {
            $product = $this->entityTypeManager->getStorage('commerce_product')->load($product_id);
        }
        $numero = $product->get('field_numero_decimo')->value;
        try {
            $abonado = $this->entityTypeManager->getStorage('abonado')->create([
                'nombre' => 'Abonado ' . $numero,
                'user_id' => \Drupal::currentUser()->id(),
                'numero' => $numero,
                'type' => 'loteria_nacional'
            ]);
            $abonado->save();

            $this->messenger()->addStatus($status_message);
            $this->logger('abonados')->info($this->t('Un nuevo abonado ha sido creado' . \Drupal::currentUser()->id()));
        } catch (\Exception $e) {
            $this->messenger()->addError('Error, por favor pruebe otra vez.'); // error para el usuario
            $this->logger('abonados')->error('Error creando el Bonado: ' . $e->getMessage()); // error detallado interno
        }
    }
}
