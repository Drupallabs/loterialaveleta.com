<?php

namespace Drupal\veleta\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_payment\Plugin\Commerce\CheckoutPane\PaymentInformation as PaymentInformationBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Provides the payment information pane.
 *
 * @CommerceCheckoutPane(
 *   id = "veleta_payment_information",
 *   label = @Translation("Custom Payment information 2"),
 *   display_label = @Translation("Payment information 2"),
 *   wrapper_element = "fieldset",
 * )
 */
class PaymentInformation extends PaymentInformationBase
{

    /**
     * {@inheritdoc}
     */
    public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form)
    {
        $pane_form = parent::buildPaneForm($pane_form, $form_state, $complete_form);

        // Add an after build callback in order to make modifications on the address form.
        $pane_form['#after_build'][] = [$this, 'paneFormAfterBuild'];

        return $pane_form;
    }

    /**
     * After build callback for the pane form.
     */
    public function paneFormAfterBuild(array $pane_form, FormStateInterface $form_state)
    {
        // Get billing form element. Where it is located depends on the payment method that is chosen.
        if (isset($pane_form['add_payment_method']['billing_information'])) {
            $billing_form = &$pane_form['add_payment_method']['billing_information'];
        } elseif (isset($pane_form['billing_information'])) {
            $billing_form = &$pane_form['billing_information'];
        } else {
            // No billing information found.
            return $pane_form;
        }

        // Get the address form element.
        $address_form = &$billing_form['address']['widget']['0']['address'];

        // Add element validation callback to autofill the address.
        $billing_form['#element_validate'] = array_merge(
            [[$this, 'profileSelectValidate']],
            \Drupal::service('element_info')->getInfoProperty('commerce_profile_select', '#element_validate', [])
        );

        // Set all address fields to non-required.
        foreach (Element::children($address_form) as $key) {
            $address_form[$key]['#required'] = FALSE;
        }

        // Hide the address form.
        $address_form['#access'] = FALSE;

        return $pane_form;
    }

    /**
     * Element validation callback for the profile select element.
     */
    public function profileSelectValidate(array &$element, FormStateInterface $form_state)
    {
        // Set dummy address.
        $address = [
            'given_name' => 'A',
            'family_name' => 'A',
            'address_line1' => 'Dummy street',
            'postal_code' => '1234 AB',
            'locality' => 'Dummy city',
            'country_code' => 'NL',
        ];
        $form_state->setValue($element['address']['widget'][0]['address']['#parents'], $address);
    }
}
