<?php

namespace Drupal\commerce_redsys\PluginForm;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;

class RedsysPaymentForm extends BasePaymentOffsiteForm
{

  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {

    $form = parent::buildConfigurationForm($form, $form_state);

    /** @var \Drupal\Core\Config\ConfigFactoryInterface $factory */
    $factory = \Drupal::service('config.factory');
    $config = $factory->get('commerce_redsys.configuracion');
    //$url_pruebas = $config->get('url_pruebas');
    $url_real = $config->get('url_real');
    $version = $config->get('signatureversion');
    $clave = $config->get('clave_sha');
    $merchant_url = $config->get('merchant_url');

    $redirect_url = $url_real;

    $red = new RedsysAPI;
    $terminal = "001";
    $moneda = "978";
    $trans = "0";
    $urlOK = $form['#return_url'];
    $urlKO = $form['#cancel_url'];

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;
    //estos dos valores los vamos cambiando en cada compra

    $id = "00" . $payment->getOrder()->id(); //el valor que le damos en cada ejemplo
    $amount = $payment->getAmount()->getNumber() * 100; //el valor que le damos en cada ejemplo

    $red->setParameter('DS_MERCHANT_AMOUNT', $amount);
    $red->setParameter('DS_MERCHANT_ORDER', $id);
    $red->setParameter('DS_MERCHANT_MERCHANTCODE', $config->get('fuc'));
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

    foreach ($data as $name => $value) {
      if (isset($value)) {
        $form[$name] = ['#type' => 'hidden', '#value' => $value];
      }
    }

    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, self::REDIRECT_POST);
  }
}
