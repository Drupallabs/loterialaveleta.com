<?php

namespace Drupal\premios\Mail;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;

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
     * The Config Factory.
     *
     * @var Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * Constructs a new UserLoginEmail object.
     *
     * @param \Drupal\example\Mail\MailHandler $mail_handler
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The mail handler.
     */
    public function __construct(MailHandler $mail_handler, ConfigFactoryInterface $config_factory)
    {
        $this->mailHandler = $mail_handler;
        $this->configFactory = $config_factory;
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
        $config = $this->configFactory->get('premios.configuration');
        $params['cc'] = $config->get('email_notify');

        return $this->mailHandler->sendMail($mail_customer, $subject, $body, $params);
    }
}
