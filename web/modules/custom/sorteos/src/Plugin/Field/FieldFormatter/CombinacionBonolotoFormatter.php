<?php

namespace Drupal\sorteos\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'combinacion_bonoloto' formatter.
 *
 * @FieldFormatter(
 *   id = "combinacion_bonoloto",
 *   label = @Translation("Combinacion Bonoloto Formatter"),
 *   description = @Translation("Combinacion Bonoloto - Formatter"),
 *   field_types = {
 *     "combinacion_bonoloto",
 *   }
 * )
 */

class CombinacionBonolotoFormatter extends FormatterBase
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
                    '#markup' => $item->bola1.' - ',
                ),
                'bola2' => array(
                    '#markup' => $item->bola2.' - ',
                ),
                'bola3' => array(
                    '#markup' => $item->bola3.' - ',
                ),
                'bola4' => array(
                    '#markup' => $item->bola4.' - ',
                ),
                'bola5' => array(
                    '#markup' => $item->bola5.' - ',
                ),
                'bola6' => array(
                    '#markup' => $item->bola6.' - ',
                ),
                'reintegro' => array(
                    '#markup' => $item->reintegro.' - ',
                ),
                'complementario' => array(
                    '#markup' => $item->complementario,
                ),
            );
        }

        return $elements;
    }
}
