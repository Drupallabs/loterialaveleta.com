<?php

namespace Drupal\veleta\TwigExtension;

use Drupal\Core\Datetime\DrupalDateTime;


class DiasBote extends \Twig_Extension
{

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters()
  {
    return [
      new \Twig_SimpleFilter('diasBote', array($this, 'diasBote')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName()
  {
    return 'veleta.twig_extension_botes';
  }

  public static function diasBote($string)
  {
    $hoy = new DrupalDateTime();
    $diabote = new DrupalDateTime($string);
    $dia = $diabote->format('l');
    $hoybote = $hoy->format('l');
    $dianum = $diabote->format('j');
    $mes = $diabote->format('F');

    if ($hoybote === $dia) {
      return "Hoy";
    } else {
      return $dia . ' , ' . $dianum . ' de ' . $mes;
    }
  }
}
