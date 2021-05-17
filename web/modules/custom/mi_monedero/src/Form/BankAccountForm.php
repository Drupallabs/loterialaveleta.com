<?php

namespace Drupal\mi_monedero\Form;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;

/**
 * Form controller for Cuenta Bancaria edit forms.
 *
 * @ingroup mi_monedero
 */
class BankAccountForm extends FormBase
{

    /**
     * The current user account.
     *
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $account;

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        // Instantiates this form class.
        $instance = parent::create($container);
        $instance->account = $container->get('current_user');
        $instance->entityManager = $container->get('entity_type.manager');
        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $account = $this->account->getAccount();
        $user = $this->entityManager->getStorage('user')->load($account->id());

        $form['cuenta_bancaria'] = [
            '#type' => 'textfield',
            '#title' => 'Cuenta Bancaria',
            '#description' => 'Por favor, introduce el número de cuenta bancaria. Será utilizado para reembolsarte el dinero de tu monedero cuando lo solicites.',
            '#size' => 30,
            '#maxlength' => 30,
            '#required' => TRUE,
            '#default_value' => $user->field_cuenta_bancaria->value
        ];

        $form['actions'] = ['#type' => 'actions'];
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Guardar',
        ];

        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('cuenta_bancaria') == '') {
            $form_state->setErrorByName('cuenta_bancaria', $this->t('Por favor introduce un numero de cuenta bancaria.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $account = $this->account->getAccount();
        $user = $this->entityManager->getStorage('user')->load($account->id());
        $user->set('field_cuenta_bancaria', $form_state->getValue('cuenta_bancaria'));
        $user->save();
    }



    /*
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'mi_monedero_bankaccount';
    }
}
