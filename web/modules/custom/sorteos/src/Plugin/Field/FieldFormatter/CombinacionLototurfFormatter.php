<?php

namespace Drupal\sorteos\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'combinacion_lototurf' formatter.
 *
 * @FieldFormatter (
 *   id = "combinacion_lototurf",
 *   label = @Translation("Combinacion Lototurf"),
 *   field_types = {
 *     "combinacion_lototurf"
 *   }
 * )
 */

class CombinacionLototurfFormatter extends FormatterBase
{
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $elements = array();

        foreach ($items as $delta => $item) {

            $elements[$delta] = array(
                'bola1' => array(
                    '#markup' => $item->bola1 . ' - ',
                ),
                'bola2' => array(
                    '#markup' => $item->bola2 . ' - ',
                ),
                'bola3' => array(
                    '#markup' => $item->bola3 . ' - ',
                ),
                'bola4' => array(
                    '#markup' => $item->bola4 . ' - ',
                ),
                'bola5' => array(
                    '#markup' => $item->bola5 . ' - ',
                ),
                'bola6' => array(
                    '#markup' => $item->bola6 . ' - ',
                ),
                'caballo' => array(
                    '#markup' => $item->caballo . ' - ',
                ),
                'reintegro' => array(
                    '#markup' => $item->reintegro,
                ),
            );
        }

        return $elements;
    }
}
