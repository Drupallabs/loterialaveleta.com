<?php

namespace Drupal\premios\Mail;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Premios message class.
 */
final class MailPremios
{
    /**
     * The mail handler.
     *
     * @var \Drupal\example\Mail\MailHandler
     */
    protected $mailHandler;


    /**
     * Constructs a new UserLoginEmail object.
     *
     * @param \Drupal\example\Mail\MailHandler $mail_handler
     *   The mail handler.
     */
    public function __construct(MailHandler $mail_handler)
    {
        $this->mailHandler = $mail_handler;
    }

    /**
     * Sends email.
     * 
     * @param Drupal\commerce\Entity\Commerce
     * 
     * @return bool 
     *   The message status.
     */
    public function send($commerce_order_item, $prize): bool
    {
        $commerce_order = $commerce_order_item->getOrder();
        $subject = new TranslatableMarkup('Notificacion Premio Pedido ' . $commerce_order->id());
        $body = [
            '#markup' => '',
        ];

        $customer = $commerce_order->getCustomer();
        $mail_customer = $customer->get('mail')->value;
        $params['commerce_order'] = $commerce_order;
        $params['commerce_order_item'] = $commerce_order_item;
        $params['prize'] = $prize;
        return $this->mailHandler->sendMail($mail_customer, $subject, $body, $params);
    }
}
