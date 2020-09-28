<?php
namespace Drupal\botes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BotesConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['botes.config_form'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'botes_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('botes.config_form');

    $form['url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Url api'),
      '#description' => $this->t('Por favor Introduzca la url de la api que devuelve los botes.'),
      '#default_value' => $config->get('url'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('botes.config_form')
      ->set('url', $form_state->getValue('url'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
