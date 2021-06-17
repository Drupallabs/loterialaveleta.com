<?php

namespace Drupal\empresas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ComprobarResultadosForm.
 */
class EmpresasContactoForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'empresas_contacto_formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => 'Nombre',
      '#maxlength' => 64,
      '#required' => true,
    ];

    $form['empresa'] = [
      '#type' => 'textfield',
      '#title' => 'Empresa',
      '#maxlength' => 64,
      '#required' => true,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#maxlength' => 64,
      '#required' => true,
    ];
    $form['telefono'] = [
      '#type' => 'textfield',
      '#title' => 'Teléfono',
      '#maxlength' => 15,
      //  '#size' => 64,
    ];
    $form['comentarios'] = [
      '#type' => 'textarea',
      '#title' => 'Comentarios'
    ];
    $form['captcha'] = array(
      '#type' => 'captcha',
      '#captcha_type' => 'captcha/Image',
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Enviar !!',
    ];
    $form['#theme'] = 'empresas-contacto';
   
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $params = $form_state->getValues();

    $mail_manager = \Drupal::service('plugin.manager.mail');
    $result = $mail_manager->mail('empresas', 'contacto', 'hola@loterialaveleta.com', \Drupal::service("language.default")->get()->getId(), $params);
    //$result = $mail_manager->mail('contacto', 'contacto_message', 'hola@loterialaveleta.com', $params);
    if ($result['result'] == TRUE) {
      $this->messenger()->addMessage($this->t('Your message has been sent.'));
    } else {
      $this->messenger()->addMessage($this->t('There was a problem sending your message and it was not sent.'), 'error');
    }
  }
}
