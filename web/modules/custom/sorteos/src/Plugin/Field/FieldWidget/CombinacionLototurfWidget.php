<?php

namespace Drupal\sorteos\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'combinacion_lototurf' widget.
 *
 * @FieldWidget (
 *   id = "combinacion_lototurf",
 *   label = @Translation("Combinacion Lototurf"
 * ),
 *   field_types = {
 *     "combinacion_lototurf"
 *   }
 * )
 */
class CombinacionLototurfWidget extends WidgetBase
{
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
    {
        $element['bola1'] = array(
            '#type' => 'number',
            '#title' => 'Bola 1',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola1,
        );
        $element['bola2'] = array(
            '#type' => 'number',
            '#title' => 'Bola 2',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola2,
        );
        $element['bola3'] = array(
            '#type' => 'number',
            '#title' => 'Bola 3',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola3,
        );
        $element['bola4'] = array(
            '#type' => 'number',
            '#title' => 'Bola 4',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola4,
        );
        $element['bola5'] = array(
            '#type' => 'number',
            '#title' => 'Bola 5',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola5,
        );
        $element['bola6'] = array(
            '#type' => 'number',
            '#title' => 'Bola 6',
            '#size' => 5,
            '#default_value' => $items[$delta]->bola6,
        );
        $element['caballo'] = array(
            '#type' => 'number',
            '#title' => 'Caballo',
            '#size' => 5,
            '#default_value' => $items[$delta]->caballo,
        );
        $element['reintegro'] = array(
            '#type' => 'number',
            '#title' => 'Reintegro',
            '#size' => 5,
            '#default_value' => $items[$delta]->reintegro,
        );

        // If cardinality is 1, ensure a label is output for the field by wrapping
        // it in a details element.
        if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
            $element += array(
                '#type' => 'fieldset',
                '#attributes' => array('class' => array('container-inline')),
            );
        }

        return $element;
    }
}
