<?php

namespace Drupal\sorteos;

use Drupal\sorteos\Entity\SorteoType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Drupal\entity\BulkFormEntityListBuilder;

class SorteoListBuilder extends EntityListBuilder
{
    /**
     * The date formatter.
     *
     * @var \Drupal\Core\Datetime\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * Constructs a new OrderListBuilder object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
     *   The date formatter.
     */
    public function __construct(EntityTypeInterface $entity_type, EntityTypeManagerInterface $entity_type_manager, DateFormatterInterface $date_formatter)
    {
        parent::__construct($entity_type, $entity_type_manager->getStorage($entity_type->id()));

        $this->dateFormatter = $date_formatter;
    }

    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('entity_type.manager'),
            $container->get('date.formatter')
        );
    }
    public function load()
    {

        $entity_query = \Drupal::service('entity.query')->get('sorteo')->sort('id', 'DESC');
        $header = $this->buildHeader();

        $entity_query->pager(25);
        $entity_query->tableSort($header);

        $uids = $entity_query->execute();

        return $this->storage->loadMultiple($uids);
    }

    public function buildHeader()
    {
        $header['id'] = ' ID';
        $header['numero'] = 'ID Sorteo';
        $header['fecha'] = 'Fecha Sorteo';
        $header['hora'] = 'Hora Sorteo';
        $header['dia_semana'] = 'Dia Semana';
        $header['tipo'] = 'Tipo Sorteo';
        $header['name'] = 'Nombre';
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /** @var \Drupal\sorteos\Entity\SorteoInterface $entiti */
        $sorteo_type = SorteoType::load($entity->bundle());
        $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $entity->getFecha());
        /* @var $entity \Drupal\sorteos\Entity\Sorteo */
        $row['id'] = $entity->id();
        $row['numero'] = $entity->getIdSorteo();
        $row['fecha'] =  $dtime->format('d/m/Y');
        $row['hora'] = $dtime->format('H:i:s');
        $row['dia_semana'] = $entity->getDiaSemana();
        $row['tipo'] = $sorteo_type->label();
        $row['name'] = Link::fromTextAndUrl(
            $entity->label(),
            new Url(
                'entity.sorteo.canonical',
                [
                    'sorteo' => $entity->id(),
                ]
            )
        );
        return $row + parent::buildRow($entity);
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultOperations(EntityInterface $entity)
    {
        $operations = parent::getDefaultOperations($entity);


        return $operations;
    }
}
