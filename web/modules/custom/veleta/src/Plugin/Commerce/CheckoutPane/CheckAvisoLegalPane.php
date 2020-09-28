<?php

namespace Drupal\veleta\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Muestra el Checkbox de Aviso Legal
 *
 * @CommerceCheckoutPane(
 *   id = "veleta_check_avisolegal",
 *   label = @Translation("Check Aceptar Proteccion de Datos")
 * )
 */
class CheckAvisoLegalPane extends CheckoutPaneBase
{

    /**
     * {@inheritdoc}
     */
    public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form)
    {
        $pane_form['datos'] = [
            '#type' => 'checkbox',
            '#title' => 'Por favor, Acepta nuestra <br class="mobile-break"> <a href="https://loterialaveleta.com/proteccion-de-datos" target="_blank">Política de Proteccion de Datos</a>',
            '#required' => true,
            '#prefix' => '<div class="form-radios">',
            '#suffix' => '</div>'
        ];
        return $pane_form;
    }
}
