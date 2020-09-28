<?php
namespace Drupal\veleta\Theme;
echo "3333"; die; 
use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\Core\Routing\RouteMatchInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

  public function applies(RouteMatchInterface $route_match) {
    // Use this theme on a certain route.
    // return $route_match->getRouteName() == 'example_route_name';
    echo  $route_match->getRouteName(); die;
    // Or use this for more than one route:
    $possible_routes = array(
        'veleta.led'
    );

    return (in_array($route_match->getRouteName(), $possible_routes));
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    // Here you return the actual theme name.
    return 'stark';
  }

}

 ?>
