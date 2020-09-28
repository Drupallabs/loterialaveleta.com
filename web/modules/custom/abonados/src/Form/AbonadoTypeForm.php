<?php

namespace Drupal\abonados\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AbonadoTypeForm.
 */
class AbonadoTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $abonado_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $abonado_type->label(),
      '#description' => $this->t("Label for the Abonado type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $abonado_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\abonados\Entity\AbonadoType::load',
      ],
      '#disabled' => !$abonado_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $abonado_type = $this->entity;
    $status = $abonado_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Abonado type.', [
          '%label' => $abonado_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Abonado type.', [
          '%label' => $abonado_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($abonado_type->toUrl('collection'));
  }

}
