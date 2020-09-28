<?php

/**
 * @file
 * Contains \Drupal\contacto\Form\ContactoForm.
 */

namespace Drupal\contacto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactoForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'contacto_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $options = ['absolute' => TRUE, 'attributes' => ['class' => 'this-class']];
    $link_object = \Drupal\Core\Link::createFromRoute('proteccion de datos', 'entity.node.canonical', ['node' => 7]);
    $link = $link_object->toString();
    $linkp = $link->__toString();
    $form['nombre'] = array(
      '#type' => 'textfield',
      '#title' => t('Nombre:'),
      '#required' => TRUE,
      //'#attributes' => array('class' => array('form-control')),
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
    );
    $form['telefono'] = array(
      '#type' => 'textfield',
      '#title' => t('Teléfono:'),
    );
    $form['mensaje'] = array(
      '#type' => 'textarea',
      '#title' => t('Mensaje'),
    );
    $form['acepto'] = array(
      '#type' => 'checkbox',
      '#required' => TRUE,
      '#title' => 'Acepto la política de ' . $linkp,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
      '#button_type' => 'primary',
      '#attributes' => array('class' => array('btn btn-primary')),
    );
    $form['#theme'] = 'contacto';
    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $params = $form_state->getValues();
    $mail_manager = \Drupal::service('plugin.manager.mail');
    $result = $mail_manager->mail('contacto', 'contacto_message', 'hola@loterialaveleta.com', \Drupal::service("language.default")->get()->getId(), $params);
    //$result = $mail_manager->mail('contacto', 'contacto_message', 'hola@loterialaveleta.com', $params);
    if ($result['result'] == TRUE) {
      $this->messenger()->addMessage($this->t('Your message has been sent.'));
    } else {
      $this->messenger()->addMessage($this->t('There was a problem sending your message and it was not sent.'), 'error');
    }
  }
}
