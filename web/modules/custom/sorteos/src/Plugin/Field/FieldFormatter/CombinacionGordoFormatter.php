<?php

namespace Drupal\sorteos\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'combinacion_gordo' formatter.
 *
 * @FieldFormatter (
 *   id = "combinacion_gordo",
 *   label = @Translation("Combinacion Euromillones"),
 *   field_types = {
 *     "combinacion_gordo"
 *   }
 * )
 */

class CombinacionGordoFormatter extends FormatterBase
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
                'clave' => array(
                    '#markup' => $item->clave . ' - ',
                ),
            );
        }

        return $elements;
    }
}
