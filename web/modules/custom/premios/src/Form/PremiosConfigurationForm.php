<?php

namespace Drupal\premios\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class PremiosConfigurationForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'premios_admin_configuration';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            'premios.configuration',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
    {

        $config = $this->config('premios.configuration');
        $form['resultados'] = array(
            '#type'  => 'fieldset',
            '#title' => $this->t('Premios settings'),
        );
        $form['resultados']['email_notify'] = array(
            '#type'          => 'email',
            '#title'         => $this->t('Direccion envio copia premios'),
            '#default_value' => $config->get('email_notify'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('premios.configuration')
            ->set('email_notify', $form_state->getValue('email_notify'))
            ->save();
    }
}
