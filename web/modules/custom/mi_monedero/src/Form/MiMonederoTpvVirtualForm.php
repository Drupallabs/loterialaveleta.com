<?php

namespace Drupal\mi_monedero\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\commerce_redsys_payment\RedsysAPI as RedsysAPI;

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
        return 'mi_monedero_tpvvirtual';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $tempstore = \Drupal::service('tempstore.private')->get('mi_monedero');
        $config = $this->factory->get('commerce_payment.commerce_payment_gateway.pago_con_tarjeta_redsys');

        $clave = $config->get('clave');
        $fuc = $config->get('fuc');
        dump($clave);
        dump($fuc);
        dump($config);
        die;
        dump($tempstore);
        $version = $config->get('signatureversion');
        $clave = $config->get('clave_sha');
        $merchant_url = $config->get('merchant_url');

        $red = new RedsysAPI;
        $fuc = "049095037";
        $terminal = "001";
        $moneda = "978";
        $trans = "0";
        $urlOK = $config->get('monedero_urlok');
        $urlKO = $config->get('monedero_urlnook');

        //estos dos valores los vamos cambiando en cada compra
        $id = $this->currentUser()->id() . "-" . substr(str_shuffle("0123456789"), 0, 6);
        $cantidad  = $tempstore->get('cantidad') * 100;

        $red->setParameter('DS_MERCHANT_AMOUNT', $cantidad);
        $red->setParameter('DS_MERCHANT_ORDER', $id);
        $red->setParameter('DS_MERCHANT_MERCHANTCODE', $fuc);
        $red->setParameter('DS_MERCHANT_CURRENCY', $moneda);
        $red->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $trans);
        $red->setParameter('DS_MERCHANT_TERMINAL', $terminal);
        $red->setParameter('DS_MERCHANT_MERCHANTURL', $merchant_url);
        $red->setParameter('DS_MERCHANT_URLOK', $urlOK);
        $red->setParameter('DS_MERCHANT_URLKO', $urlKO);

        $params = $red->createMerchantParameters();
        $signature = $red->createMerchantSignature($clave);
        $data = [
            'Ds_SignatureVersion' => $version,
            'Ds_MerchantParameters' => $params,
            'Ds_Signature' => $signature
        ];

        $form['#action'] = $config->get('url_real');
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
