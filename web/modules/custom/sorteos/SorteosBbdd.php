<?php

namespace Drupal\sorteos;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
/**
 * Clase que obtiene sorteos de base de datos
 */
class SorteosBbdd
{

    /**
     * The db connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->date_format = 'Y-m-dTH:i:s';
        $this->hoy = new DrupalDateTime();
        $current_time = DrupalDateTime::createFromTimestamp(time());
        $this->ahora = $current_time->format(DateTimeItem::DATETIME_STORAGE_FORMAT);
        //$this->resultados = [];
    }

    public static function create(ContainerInterface $container)
    {
        return new static($container->get('database'));
    }
    /**
     * Devuelve el proximo sorteo a celebrar a partir de ahora
     */
    public function proximoSorteo($gameid) {

        if($gameid) {
            return $gameid.'777777';
        }
    }

}