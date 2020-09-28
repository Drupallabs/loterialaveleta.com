<?php
namespace Drupal\veleta\Theme;

use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\Core\Routing\RouteMatchInterface;

class VeletaNegotiator implements ThemeNegotiatorInterface {

  public function applies(RouteMatchInterface $route_match) {
    // Use this theme on a certain route.
   return $route_match->getRouteName() == 'veleta.led';
    //$route_match->getRouteName();
    // Or use this for more than one route:
  /*  $possible_routes = array(
        'veleta.led'
    );

    return (in_array($route_match->getRouteName(), $possible_routes));*/
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    // Here you return the actual theme name.
    return 'veletaled';
  }

}

 ?>
