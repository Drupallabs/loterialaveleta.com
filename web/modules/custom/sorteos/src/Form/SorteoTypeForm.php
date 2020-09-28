<?php

namespace Drupal\sorteos\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for creating/editing SorteoType entities
 */
class SorteoTypeForm extends EntityForm
{

    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state)
    {
        $form = parent::form($form, $form_state);

        /** @var \Drupal\sorteos\Entity\SorteoTypeInterface $sorteo_type */
        $sorteo_type = $this->entity;
        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Label'),
            '#maxlength' => 255,
            '#default_value' => $sorteo_type->label(),
            '#description' => $this->t('Label for the Sorteo type.'),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $sorteo_type->id(),
            '#machine_name' => [
                'exists' => '\Drupal\products\Entity\SorteoType::load',
            ],
            '#disabled' => !$sorteo_type->isNew(),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        $sorteo_type = $this->entity;
        $status = $sorteo_type->save();

        switch ($status) {
            case SAVED_NEW:
                \Drupal::messenger()->addStatus($this->t('Created the %label Sorteo type.', [
                    '%label' => $sorteo_type->label(),
                ]));
                break;

            default:
                \Drupal::messenger()->addStatus($this->t('Saved the %label Sorteo type.', [
                    '%label' => $sorteo_type->label(),
                ]));
        }
        $form_state->setRedirectUrl($sorteo_type->toUrl('collection'));
    }
}
