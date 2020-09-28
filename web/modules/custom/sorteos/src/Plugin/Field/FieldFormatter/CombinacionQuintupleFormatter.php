<?php

namespace Drupal\sorteos\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'combinacion_quintuple' formatter.
 *
 * @FieldFormatter(
 *   id = "combinacion_quintuple",
 *   label = @Translation("Combinacion Quintuple"),
 *   description = @Translation("Combinacion Quintuple"),
 *   field_types = {
 *     "combinacion_quintuple",
 *   }
 * )
 */

class CombinacionQuintupleFormatter extends FormatterBase
{
    /**
     * {@inheritdoc}
     */
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
                    '#markup' => $item->bola6 ,
                ),
            );
        }

        return $elements;
    }
}
