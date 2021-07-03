<?php

namespace Drupal\commerce_redsys\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Exception\DeclineException;
use Drupal\commerce_payment\Exception\InvalidResponseException;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;
use Drupal\commerce_price\Price;

/**
 * Provides the Redsys offsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "redsys_redirect_checkout",
 *   label = @Translation("Redys (Redirect to redsys)"),
 *   display_label = @Translation("Redsys"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_redsys\PluginForm\RedsysPaymentForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "mastercard", "visa", "maestro"
 *   },
 *   requires_billing_information = FALSE,
 * )
 */

class RedirectCheckout extends OffsitePaymentGatewayBase implements RedsysInterface
{

  public function onReturn(OrderInterface $order, Request $request)
  {
    $orderid = $order->id();
    if (empty($orderid)) {
      throw new PaymentGatewayException('Invoice id missing for this transaction.');
    }
    $logger = \Drupal::logger('commerce_redsys');

    $logger->notice('Hemos recibido tu pago');
    // Common response processing for both redirect back and async notification.
    $payment = $this->processFeedback($request);

    // Do not update payment state here - it should be done from the received
    // notification only, and considering that usually notification is received
    // even before the user returns from the off-site redirect, at this point
    // the state tends to be already the correct one.
  }

  /**
   * {@inheritdoc}
   */
  public function onCancel(OrderInterface $order, Request $request)
  {
    //parent::onCancel($order, $request);
    $this->messenger()->addMessage($this->t('Has cancelado el @gateway. Por favor, inténtalo de nuevo o elige una forma de pago diferente', [
      '@gateway' => $this->getDisplayLabel(),
    ]));
  }


  public function onNotify(Request $request)
  {

   // $logger = \Drupal::logger('commerce_redsys');
   // $logger->notice('entra en onNotify' . $request->query->get('Ds_SignatureVersion'));

    parent::onNotify($request);
  }

  /**
   * Common response processing for both redirect back and async notification.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Drupal\commerce_payment\Entity\PaymentInterface|null
   *   The payment entity, or NULL in case of an exception.
   *
   * @throws InvalidResponseException
   *   An exception thrown if response SHASign does not validate.
   * @throws DeclineException
   *   An exception thrown if payment has been declined.
   */
  private function processFeedback(Request $request)
  {

    /** @var \Drupal\Core\Config\ConfigFactoryInterface $factory */
    $factory = \Drupal::service('config.factory');
    $config = $factory->get('commerce_redsys.configuracion');
    $clave = $config->get('clave_sha');

    $red = new RedsysAPI;
    $logger = \Drupal::logger('commerce_redsys');

    $version = $request->query->get('Ds_SignatureVersion');
    $signature = $request->query->get('Ds_Signature');
    $params = $request->query->get('Ds_MerchantParameters');

    $decodec = $red->decodeMerchantParameters($params);

    $signatureCalculada = $red->createMerchantSignatureNotif($clave, $params);

    if ($signatureCalculada === $signature) {
      
      $DsErrorCode = $red->getParameter("Ds_ErrorCode");
      $DsResponse = $red->getParameter("Ds_Response");
      $authcode = $red->getParameter("Ds_AuthorisationCode");
      $amount = $red->getParameter("Ds_Amount");
      $order = $red->getParameter("Ds_Order");
      $currency = $red->getParameter("Ds_Currency");

      $price = strval($amount / 100);
      //%echo $price; die;
      $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
      $payment = $payment_storage->create([
        'state' => 'complete',
        'amount' => new Price($price, "€"),
        'currency_code' => $currency,
        'payment_gateway' => $this->entityId,
        'order_id' => $order,
        'remote_id' => $authcode,
        'remote_state' => $codigoRespuesta,
      ]);

      //$logger->info('Guardando informacion de Pago. Pedido:'.$order );
      $payment->save();
      \Drupal::messenger()->addStatus('El pago fue recibido. Gracias');

      //$logger->info('informacion de pago guardada con exito ');

      return $payment;
    } else {
      \Drupal::messenger()->addError('Pago no recibido, Por favor, inténtalo de nuevo o elige una forma de pago diferente');
    }
  }
}
