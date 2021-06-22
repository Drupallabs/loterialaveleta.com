<?php

namespace Drupal\sorteos\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class SorteosConfigurationForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'sorteos_admin_configuration';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            'sorteos.configuration',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
    {

        $config = $this->config('sorteos.configuration');
        $form['premios'] = array(
            '#type'  => 'fieldset',
            '#title' => $this->t('Sorteos settings'),
        );
        $form['premios']['email'] = array(
            '#type'          => 'email',
            '#title'         => $this->t('Email'),
            '#default_value' => $config->get('email'),
            '#description'  => $this->t('Email for send a error logging.')
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('sorteos.configuration')
            ->set('email', $form_state->getValue('email'))
            ->save();
    }
}
