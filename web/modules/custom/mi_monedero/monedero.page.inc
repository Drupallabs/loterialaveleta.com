<?php

/**
 * @file
 * Contains monedero.page.inc.
 *
 * Page callback for Monedero entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Monedero templates.
 *
 * Default template: monedero.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_monedero(array &$variables) {
  // Fetch Monedero Entity Object.
  $monedero = $variables['elements']['#monedero'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
