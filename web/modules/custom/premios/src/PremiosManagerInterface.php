<?php

namespace Drupal\premios;

use Drupal\commerce_product\Entity\ProductInterface;

interface PremiosManagerInterface
{

    public function payPremiosProduct(ProductInterface $product, $premio);
}
