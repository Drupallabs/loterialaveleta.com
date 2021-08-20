<?php

namespace Drupal\sorteos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\sorteos\Entity\SorteoInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Defines the Sorteo entity.
 *
 * @ContentEntityType(
 *   id = "sorteo",
 *   label = @Translation("Sorteo"),
 *   bundle_label = @Translation("Sorteo type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sorteos\SorteoListBuilder",
 *     "access" = "Drupal\sorteos\Access\SorteoAccessControlHandler",
 *     "views_data" = "Drupal\sorteos\Entity\SorteoViewsData",
 *     "form" = {
 *       "default" = "Drupal\sorteos\Form\SorteoForm",
 *       "add" = "Drupal\sorteos\Form\SorteoForm",
 *       "edit" = "Drupal\sorteos\Form\SorteoForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *    "route_provider" = {
 *      "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *    }
 *   },
 *   base_table = "sorteo",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *   },
 *   links = {
 *     "canonical" = "/sorteo/{sorteo}",
 *     "add-form" = "/admin/laveleta/sorteo/add/{sorteo_type}",
 *     "edit-form" = "/admin/laveleta/sorteo/{sorteo}/edit",
 *     "delete-form" = "/admin/laveleta/sorteo/{sorteo}/delete",
 *     "collection" = "/admin/laveleta/sorteo",
 *     "add-page" = "/admin/laveleta/sorteo/add"
 *   },
 *   bundle_entity_type = "sorteo_type",
 *   field_ui_base_route = "entity.sorteo_type.edit_form"
 * )
 */
class Sorteo extends ContentEntityBase implements SorteoInterface
{
    use EntityChangedTrait;

    public function getName()
    {
        return $this->get('name')->value;
    }

    public function setName($name)
    {
        $this->set('name', $name);
        return $this;
    }

    public function getIdSorteo()
    {
        return $this->get('id_sorteo')->value;
    }

    public function setIdSorteo($id_sorteo)
    {
        $this->set('id_sorteo', $id_sorteo);
        return $this;
    }

    public function getDiaSemana()
    {
        return $this->get('dia_semana')->value;
    }

    public function setDiaSemana($dia_semana)
    {
        $this->set('dia_semana', $dia_semana);
        return $this;
    }

    public function getPremioBote()
    {
        return $this->get('premio_bote')->value;
    }

    public function setPremioBote($premio_bote)
    {
        $this->set('premio_bote', $premio_bote);
        return $this;
    }

    public function getFecha()
    {
        return $this->get('fecha')->value;
    }
    public function setFecha($fecha)
    {
        $this->set('fecha', $fecha);
        return $this;
    }

    public function getApuestas()
    {
        return $this->get('apuestas')->value;
    }
    public function setApuestas($apuestas)
    {
        $this->set('apuestas', $apuestas);
        return $this;
    }

    public function getRecaudacion()
    {
        return $this->get('recaudacion')->value;
    }
    public function setRecaudacion($recaudacion)
    {
        $this->set('recaudacion', $recaudacion);
        return $this;
    }

    public function getPremios()
    {
        return $this->get('premios')->value;
    }
    public function setPremios($premios)
    {
        $this->set('premios', $premios);
        return $this;
    }

    public function getFondoBote()
    {
        return $this->get('fondo_bote')->value;
    }
    public function setFondoBote($fondo_bote)
    {
        $this->set('fondo_bote', $fondo_bote);
        return $this;
    }

    public function getEscrutinio()
    {
        if (!$this->get('escrutinio')->isEmpty()) {

            return $this->get('escrutinio')->first()->getValue();
        } else {
            return [];
        }
    }
    public function setEscrutinio($escrutinio)
    {
        $this->get('escrutinio')->__set('escrutinio', $escrutinio);
        return $this;
    }

    public function getEscrutinioJoker()
    {
        if (!$this->get('escrutinio_joker')->isEmpty()) {

            return $this->get('escrutinio_joker')->first()->getValue();
        } else {
            return [];
        }
    }
    public function setEscrutinioJoker($escrutinio_joker)
    {
        $this->get('escrutinio_joker')->__set('escrutinio_joker', $escrutinio_joker);
        return $this;
    }

    public function getResultados()
    {
        return $this->get('resultados')->entity;
    }
    public function setResultados($resultados)
    {
        $this->set('resultados', $resultados);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }


    public function getFechaSimple()
    {
        $fecha = new DrupalDateTime($this->get('fecha')->value);
        return $fecha->format('d F');
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);
        $fields['id_sorteo'] = BaseFieldDefinition::create('string')
            ->setLabel('Id Sorteo')->setDescription('')
            ->setSettings([
                'max_length' => 25,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above',
                'weight' => -4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => -4,
            ])
            ->setRequired(TRUE)
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel('Nombre')
            ->setDescription('')
            ->setRequired(TRUE)
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
        $fields['fecha'] = BaseFieldDefinition::create('datetime')
            ->setLabel('Fecha Sorteo')
            ->setDescription('La Fecha y hora de Celebracion del Sorteo.')
            ->setRequired(TRUE)
            ->setSettings([
                'datetime_type' => 'datetime'
            ])
            ->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'datetime_default',
                'settings' => [],
                'weight' => -1,
            ])
            ->setDisplayOptions('form', [
                'type' => 'datetime_default',
                'weight' => -1,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);


        $fields['dia_semana'] = BaseFieldDefinition::create('string')
            ->setLabel('Dia Semana')
            ->setDescription('Dia de la semana de la celebración del sorteo')
            ->setSettings([
                'max_length' => 25,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 0,
            ])
            ->setRequired(TRUE)
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 0,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['premio_bote'] = BaseFieldDefinition::create('string')
            ->setLabel('Premio Bote')->setDescription('Bote que se ofrece')
            ->setSettings([
                'max_length' => 25,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 1,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 1,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['apuestas'] = BaseFieldDefinition::create('string')
            ->setLabel('Apuestas Recibidas')->setDescription('')
            ->setSettings([
                'max_length' => 50,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 2,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 2,
            ])
            ->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE);

        $fields['recaudacion'] = BaseFieldDefinition::create('string')
            ->setLabel('Recaudacion')->setDescription('')
            ->setSettings([
                'max_length' => 50,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 3,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 3,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['premios'] = BaseFieldDefinition::create('string')
            ->setLabel('Premios entregados')->setDescription('')
            ->setSettings([
                'max_length' => 50,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 4,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['fondo_bote'] = BaseFieldDefinition::create('string')
            ->setLabel('Fondo Bote')->setDescription('')
            ->setSettings([
                'max_length' => 50,
            ])
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above', 'type' => 'string',
                'weight' => 5,
            ])
            ->setDisplayOptions('form', [
                'type' => 'number',
                'weight' => 5,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['escrutinio'] = BaseFieldDefinition::create('map')
            ->setLabel('Escrutinio')->setDescription('')
            ->setDisplayConfigurable('form', FALSE)
            ->setDisplayConfigurable('view', FALSE)
            ->setRequired(FALSE);

        $fields['escrutinio_joker'] = BaseFieldDefinition::create('map')
            ->setLabel('Escrutinio Joker')->setDescription('')
            ->setDisplayConfigurable('form', FALSE)
            ->setDisplayConfigurable('view', FALSE)
            ->setRequired(FALSE);

        $fields['resultados'] = BaseFieldDefinition::create('file')
            ->setLabel('Resultados')
            ->setRequired(FALSE)
            ->setDescription('Introduce el PDF con los resultados para mostrar en la pantalla resultados.')
            ->setSettings([
                'uri_scheme' => 'public',
                'file_directory' => 'resultados/[date:custom:Y]-[date:custom:m]',
                'file_extensions' => 'pdf',
                'max_filesize' => '3000 KB',
            ])
            ->setDisplayOptions('form', array(
                'type' => 'file',
                'weight' => -1,
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel('Creado')
            ->setDescription('Cuando fue creado el sorteo.');

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel('Modificado')
            ->setDescription('Cuando fue modificado el sorteo.');

        return $fields;
    }
}
