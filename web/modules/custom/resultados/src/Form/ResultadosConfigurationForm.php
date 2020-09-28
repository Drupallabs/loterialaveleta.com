<?php
namespace Drupal\resultados\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ResultadosConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'resultados_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'resultados.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('resultados.settings');
    //$state  = \Drupal::state();
    $form["#attributes"]["autocomplete"] = "off";
    $form['resultados'] = array(
      '#type'  => 'fieldset',
      '#title' => $this->t('Resultados settings'),
    );
    $form['resultados']['url'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Iguana API URL'),
      '#default_value' => $config->get('url'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('resultados.settings')
      ->set('url', $form_state->getValue('url'))
      ->save();
  }

}
