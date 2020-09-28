<?php

namespace Drupal\commerce_redsys\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface;
//use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\SupportsAuthorizationsInterface;
//use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\SupportsRefundsInterface;

/**
 * Pro vides the interface for the AuthorizeNet payment gateway.
 */
interface RedsysInterface extends OffsitePaymentGatewayInterface {

}
