<?php

namespace Drupal\botes;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Database\Connection;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;


/**
 * Clase que obtiene los botes de los sorteos que hay en bbdd de los dias futuros
 */
class BotesBbdd
{
    private $bote;

    /**
     * The db connection.
     * 
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        //$this->hoy = new DrupalDateTime();
        $current_time = DrupalDateTime::createFromArray(array('year' => date('Y'), 'month' => date('m'), 'day' => date('d')));
        $this->ahora = $current_time->format(DateTimeItem::DATETIME_STORAGE_FORMAT);
        // "2021-07-26T00:00:00" 
        $this->bote = (object) [
            'fecha_sorteo' => null,
            'tenthPrice' => null,
            'dia_semana' => null,
            'premio_bote' => null,
            'id_sorteo' => null,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static($container->get('database'));
    }

    public function getBote($gameid)
    {
        if (!$gameid) {
            return null;
        } else {
            switch ($gameid) {
                case "LNAC":
                    return $this->queryBote('loteria_nacional', 'LNAC');
                    break;
                case "EMIL":
                    return $this->queryBote('euromillones', 'EMIL');
                    break;
                case "LAPR":
                    return $this->queryBote('primitiva', 'LAPR');
                    break;
                case "BONO":
                    return $this->queryBote('bonoloto', 'BONO');
                    break;
                case "ELGR":
                    return $this->queryBote('gordo_primitiva', 'ELGR');
                    break;
                case "LAQU":
                    return $this->queryBote('quiniela', 'LAQU');
                    break;
                case "QGOL":
                    return $this->queryBote('quinigol', 'QGOL');
                    break;
                case "LOTU":
                    return $this->queryBote('lototurf', 'LOTU');
                    break;
                case "QUPL":
                    return $this->queryBote('quintuple_plus', 'QUPL');
                    break;
            }
        }
    }
    /**
     * Obtiene el proximos sorteo futuro que tiene bote a partir de ahora
     */
    private function queryBote($bundle, $gameid)
    {
        // en Base de datos sacamos cual es el proximo sorteo.
        $sorteos = $this->connection->query("SELECT s.id, s.id_sorteo, s.type, s.fecha, s.premio_bote, s.dia_semana FROM sorteo s WHERE type = :bundle AND fecha >= :fecha ORDER by s.fecha ASC LIMIT 1", [
            ':bundle' => $bundle, ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);


        if ($sorteo == null) {
            return $this->bote;
        } else {
            return $this->filtraDatosBbdd($sorteo);
        }
    }

    private function filtraDatosBbdd($sorteo)
    {
        if ($sorteo->premio_bote == 0 && $sorteo) {
            return null;
        }
        return (object) [
            'id' => $sorteo->id,
            'type' => $sorteo->type,
            'fecha_sorteo' => $sorteo->fecha,
            'tenthPrice' => '',
            'dia_semana' => $sorteo->dia_semana,
            'premio_bote' => $sorteo->premio_bote,
            'id_sorteo' => $sorteo->id_sorteo,
        ];
    }
}
