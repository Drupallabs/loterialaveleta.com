<?php

/**
 * @file
 * Contains abonado.page.inc.
 *
 * Page callback for Abonado entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Abonado templates.
 *
 * Default template: abonado.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_abonado(array &$variables) {
  // Fetch Abonado Entity Object.
  $abonado = $variables['elements']['#abonado'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
