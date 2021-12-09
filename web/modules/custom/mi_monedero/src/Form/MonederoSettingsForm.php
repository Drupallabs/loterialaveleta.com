<?php

namespace Drupal\mi_monedero\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MonederoSettingsForm.
 *
 * @ingroup mi_monedero
 */
class MonederoSettingsForm extends ConfigFormBase
{

  /**
   * Defines the settings form for Monedero entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('mi_monedero.configuracion');
    $form['monedero'] = array(
      '#type'  => 'fieldset',
      '#title' => $this->t('Configuraciones de Mi Monedero'),
    );

    $form['monedero']['activate_tpv'] = array(
      '#type'          => 'checkbox',
      '#title'         => 'Activar Añadir Saldo con Pago con tarjeta y Redireccion a TPV',
      '#default_value' => $config->get('activate_tpv'),
    );

    $form['tpv'] = array(
      '#type'  => 'fieldset',
      '#title' => $this->t('Configuraciones de La Pasarela de Pago Commerce Redsys'),
    );
    $form['tpv']['merchant_url'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Merchant Url',
      '#default_value' => $config->get('merchant_url'),
    );
    $form['tpv']['redirect_url'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Redirect Url',
      '#default_value' => $config->get('redirect_url'),
    );
    $form['tpv']['signature_version'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Signature Version',
      '#default_value' => $config->get('signature_version'),
    );
    $form['tpv']['signature'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Signature',
      '#default_value' => $config->get('signature'),
    );
    $form['tpv']['merchant_code'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Merchant Code',
      '#default_value' => $config->get('merchant_code'),
    );
    $form['tpv']['terminal'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Terminal',
      '#default_value' => $config->get('terminal'),
    );
    $form['tpv']['currency'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Moneda',
      '#default_value' => $config->get('currency'),
    );
    $form['tpv']['transaction_type'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Transaction Type',
      '#default_value' => $config->get('transaction_type'),
    );
    $form['tpv']['url_ok'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url OK',
      '#default_value' => $config->get('url_ok'),
    );
    $form['tpv']['url_nook'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Url NO OK',
      '#default_value' => $config->get('url_nook'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('mi_monedero.configuracion')
      ->set('activate_tpv', $form_state->getValue('activate_tpv'))
      ->set('merchant_url', $form_state->getValue('merchant_url'))
      ->set('redirect_url', $form_state->getValue('redirect_url'))
      ->set('signature_version', $form_state->getValue('signature_version'))
      ->set('signature', $form_state->getValue('signature'))
      ->set('merchant_code', $form_state->getValue('merchant_code'))
      ->set('terminal', $form_state->getValue('terminal'))
      ->set('currency', $form_state->getValue('currency'))
      ->set('transaction_type', $form_state->getValue('transaction_type'))
      ->set('url_ok', $form_state->getValue('url_ok'))
      ->set('url_nook', $form_state->getValue('url_nook'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'mi_monedero.configuracion',
    ];
  }
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return 'mi_monedero_settings';
  }
}
