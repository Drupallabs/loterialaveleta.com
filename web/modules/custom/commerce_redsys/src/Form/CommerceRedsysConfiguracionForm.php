<?php

namespace Drupal\commerce_redsys\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

class CommerceRedsysConfiguracionForm extends ConfigFormBase
{

  public function getFormId()
  {
    return 'commerceredsys_configuracion';
  }

  protected function getEditableConfigNames()
  {
    return [
      'commerce_redsys.configuracion',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
  {
    $config = $this->config('commerce_redsys.configuracion');

    $form['commerce_redsys'] = array(
      '#type'  => 'fieldset',
      '#title' => $this->t('Configuracion Pasarela de Pago Redsys'),
    );
    $form['commerce_redsys']['url_pruebas'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url Pruebas',
      '#default_value' => $config->get('url_pruebas'),
    );
    $form['commerce_redsys']['url_real'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url Real',
      '#default_value' => $config->get('url_real'),
    );
    $form['commerce_redsys']['signatureversion'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Signature Version',
      '#default_value' => $config->get('signatureversion'),
    );

    $form['commerce_redsys']['clave'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Clave de comercio',
      '#default_value' => $config->get('clave'),
    );
    $form['commerce_redsys']['clave_sha'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Clave de comercio SHA-256',
      '#default_value' => $config->get('clave_sha'),
    );
    $form['commerce_redsys']['fuc'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Fuc',
      '#default_value' => $config->get('fuc'),
    );
    $form['commerce_redsys']['url_comercio'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url del comercio',
      '#default_value' => $config->get('url_comercio'),
    );
    $form['commerce_redsys']['merchant_url'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Merchant Url',
      '#default_value' => $config->get('merchant_url'),
      '#description' => 'Url que recibe las notificaciones por POST'
    );
    $form['commerce_redsys']['url_ok'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url OK',
      '#default_value' => $config->get('url_ok'),
    );
    $form['commerce_redsys']['url_nook'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Ulr No OK',
      '#default_value' => $config->get('url_nook'),
    );
    $form['commerce_redsys']['monedero_urlok'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Mi Monedero Url OK',
      '#default_value' => $config->get('monedero_urlok'),
      '#description' => 'Url OK para el pago con tarjete en mi monedero'
    );
    $form['commerce_redsys']['monedero_urlnook'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Mi Monedero URL No OK',
      '#default_value' => $config->get('monedero_urlnook'),
      '#description' => 'Url No OK para el pago con tarjeta en mi monedero'
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('commerce_redsys.configuracion')
      ->set('url_pruebas', $form_state->getValue('url_pruebas'))
      ->set('url_real', $form_state->getValue('url_real'))
      ->set('signatureversion', $form_state->getValue('signatureversion'))
      ->set('clave', $form_state->getValue('clave'))
      ->set('clave_sha', $form_state->getValue('clave_sha'))
      ->set('url_comercio', $form_state->getValue('url_comercio'))
      ->set('merchant_url', $form_state->getValue('merchant_url'))
      ->set('fuc', $form_state->getValue('fuc'))
      ->set('url_ok', $form_state->getValue('url_ok'))
      ->set('url_nook', $form_state->getValue('url_nook'))
      ->set('monedero_urlok', $form_state->getValue('monedero_urlok'))
      ->set('monedero_urlnook', $form_state->getValue('monedero_urlnook'))
      ->save();
  }
}
