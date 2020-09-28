<?php

namespace Drupal\sorteos\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'combinacion_euromillones' field type.
 *
 * @FieldType (
 *   id = "combinacion_euromillones",
 *   label = @Translation("Combinacion Euromillones"),
 *   description = @Translation(""),
 *   default_widget = "combinacion_euromillones",
 *   default_formatter = "combinacion_euromillones"
 * )
 */
class CombinacionEuromillonesItem extends FieldItemBase
{
    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition)
    {
        return array(
            'columns' => array(
                'bola1' => array(
                    'type' => 'int',
                    'not null' => FALSE,
                ),
                'bola2' => array(
                    'type' => 'int',
                    'not null' => FALSE,
                ),
                'bola3' => array(
                    'type' => 'int',
                    'not null' => TRUE,
                ),
                'bola4' => array(
                    'type' => 'int',
                    'not null' => TRUE,
                ),
                'bola5' => array(
                    'type' => 'int',
                    'not null' => TRUE,
                ),
                'estrella1' => array(
                    'type' => 'int',
                    'not null' => TRUE,
                ),
                'estrella2' => array(
                    'type' => 'int',
                    'not null' => TRUE,
                ),
            ),
        );
        //The definitions in columns work the same way as the Drupal 8 schema API which you can use for reference if you need to.
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $value1 = $this->get('bola1')->getValue();
        $value2 = $this->get('bola2')->getValue();
        $value3 = $this->get('bola3')->getValue();
        $value4 = $this->get('bola4')->getValue();
        $value5 = $this->get('bola5')->getValue();
        $value6 = $this->get('estrella2')->getValue();
        $value7 = $this->get('estrella1')->getValue();
        return empty($value1) && empty($value2) && empty($value3) && empty($value4) && empty($value5) && empty($value6) && empty($value7);
    }

    /**
     * {@inheritdoc}
     */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
    {
        $properties['bola1'] = DataDefinition::create('integer')
            ->setLabel('Bola 1')
            ->setSetting('unsigned', TRUE);
        $properties['bola2'] = DataDefinition::create('integer')
            ->setLabel('Bola 2')
            ->setSetting('unsigned', TRUE);
        $properties['bola3'] = DataDefinition::create('integer')
            ->setLabel('Bola 3')
            ->setSetting('unsigned', TRUE);
        $properties['bola4'] = DataDefinition::create('integer')
            ->setLabel('Bola 4')
            ->setSetting('unsigned', TRUE);
        $properties['bola5'] = DataDefinition::create('integer')
            ->setLabel('Bola 5')
            ->setSetting('unsigned', TRUE);
        $properties['estrella1'] = DataDefinition::create('integer')
            ->setLabel('Estrella 1')
            ->setSetting('unsigned', TRUE);
        $properties['estrella2'] = DataDefinition::create('integer')
            ->setLabel('Estrella 2')
            ->setSetting('unsigned', TRUE);

        return $properties;
    }
}
