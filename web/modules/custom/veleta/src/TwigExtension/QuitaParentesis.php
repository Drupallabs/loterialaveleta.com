<?php
namespace Drupal\veleta\TwigExtension;

class QuitaParentesis extends \Twig_Extension {

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('quitaParentesis', array($this, 'quitaParentesis')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'veleta.twig_extension';
  }


  public static function quitaParentesis($string) {
     preg_match_all('/\(([0-9]+?)\)/',$string,$match);

     return $match[1];

  }

}
