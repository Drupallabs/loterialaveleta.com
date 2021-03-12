<?php

namespace Drupal\premios;

use Drupal\commerce_order\Entity\OrderInterface;

/**
 * Manages premios of orders.
 *
 */
interface PremiosManagerInterface
{
    /**
     * Checkque los premios que tiene un pedido 
     *
     * @param \Drupal\commerce_order\Entity\OrderInterface $order
     *   The recurring order.
     */
    public function checkPremiosOrder(OrderInterface $order);
}
