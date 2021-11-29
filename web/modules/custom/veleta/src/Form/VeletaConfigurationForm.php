<?php

namespace Drupal\veleta\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class VeletaConfigurationForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'veleta_admin_configuration';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            'veleta.configuration',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
    {
        $config = $this->config('veleta.configuration');
        $form['veleta'] = array(
            '#type'  => 'fieldset',
            '#title' => $this->t('settings'),
        );
        $form['veleta']['email_notify_errors'] = array(
            '#type'          => 'email',
            '#title'         => $this->t('Email Error'),
            '#default_value' => $config->get('email_notify_errors'),
            '#description'  => $this->t('Email for send a erros of the website')
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('veleta.configuration')
            ->set('email_notify_errors', $form_state->getValue('email_notify_errors'))
            ->save();
    }
}
