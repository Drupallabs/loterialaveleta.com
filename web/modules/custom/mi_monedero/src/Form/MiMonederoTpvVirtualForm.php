<?php

namespace Drupal\mi_monedero\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Url;
use Drupal\mi_monedero\RedsysAPI as RedsysAPI;

class MiMonederoTpvVirtualForm extends FormBase
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
        return 'mi_monedero_tpvvirtual';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $tempstore = \Drupal::service('tempstore.private')->get('mi_monedero');
        $config = $this->factory->get('mi_monedero.configuracion');

        $signature_version = $config->get('signature_version');
        $signature = $config->get('signature');
        $merchant_url = $config->get('merchant_url');
        $merchant_code = $config->get('merchant_code');
        $currency = $config->get('currency');
        $terminal = $config->get('terminal');
        $transaction_type = $config->get('transaction_type');
        $urlOK = $config->get('url_ok');
        $urlKO = $config->get('url_nook');

        $red = new RedsysAPI;

        //estos dos valores los vamos cambiando en cada compra
        $id = $this->currentUser()->id() . "-" . substr(str_shuffle("0123456789"), 0, 6);
        $cantidad  = $tempstore->get('cantidad') * 100;

        $red->setParameter('DS_MERCHANT_AMOUNT', $cantidad);
        $red->setParameter('DS_MERCHANT_ORDER', $id);
        $red->setParameter('DS_MERCHANT_MERCHANTCODE', $merchant_code);
        $red->setParameter('DS_MERCHANT_CURRENCY', $currency);
        $red->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $transaction_type);
        $red->setParameter('DS_MERCHANT_TERMINAL', $terminal);
        $red->setParameter('DS_MERCHANT_MERCHANTURL', $merchant_url);
        $red->setParameter('DS_MERCHANT_URLOK', $urlOK);
        $red->setParameter('DS_MERCHANT_URLKO', $urlKO);

        $params = $red->createMerchantParameters();
        $signature = $red->createMerchantSignature($signature);

        $data = [
            'Ds_SignatureVersion' => $signature_version,
            'Ds_MerchantParameters' => $params,
            'Ds_Signature' => $signature
        ];

        $form['#action'] = $config->get('redirect_url');
        $form['#attached']['library'][] = 'mi_monedero/redireccion_offsite';

        foreach ($data as $name => $value) {
            if (isset($value)) {
                $form[$name] = ['#type' => 'hidden', '#value' => $value];
            }
        }
        $form['#markup'] = 'Vamos a redirigirte al servidor del pago seguro, si esto no ocurre en 10 segundos, por favor pulsa el botón de abajo.';
        $form['actions'] = ['#type' => 'actions'];
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Redirigirme al Pago seguro',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }
}
