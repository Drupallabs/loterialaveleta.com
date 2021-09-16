<?php

/**
 * @file
 * Contains empresa.page.inc.
 *
 * Page callback for Empresa entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Empresa templates.
 *
 * Default template: empresa.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_empresa(array &$variables) {
  // Fetch Empresa Entity Object.
  $empresa = $variables['elements']['#empresa'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
