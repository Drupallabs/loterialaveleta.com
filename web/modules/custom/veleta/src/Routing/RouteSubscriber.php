<?php

/**
 * @file
 * Contains \Drupal\veleta\Routing\RouteSubscriber.
 */

namespace Drupal\veleta\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase
{
  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection)
  {
    //  echo "user.login"; die; 
    if ($route = $collection->get('user.login')) {

      $route->setDefault('_title', 'Iniciar Sesion en loterialaveleta.com');
    }
  }
}
