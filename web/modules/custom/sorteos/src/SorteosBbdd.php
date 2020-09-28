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
        $this->ahora = date('Y-m-dT00:00:00');
        $this->bundles = [
            'LNAC' => 'loteria_nacional',
            'EMIL' => 'euromillones',
            'LAPR' => 'primitiva',
            'BONO' => 'bonoloto',
            'ELGR' => 'gordo_primitiva',
            'LAQU' => 'quiniela',
            'QGOL' => 'quinigol',
            'LOTU' => 'lototurf',
            'QUPL' => 'quintuple_plus'
        ];
    }

    public static function create(ContainerInterface $container)
    {
        return new static($container->get('database'));
    }
    /**
     * Devuelve el proximo sorteo a celebrar a partir de ahora
     */
    public function proximoSorteo($gameid)
    {
        if (!$gameid) {
            return null;
        } else {
            $bundle = $this->bundles[$gameid];
            switch ($bundle) {
                case "loteria_nacional":
                    //return $this->dameResultadosHoyLnac();
                    break;
                case "euromillones":
                    //return $this->dameResultadosHoyEmil();
                    break;
                case "primitiva":
                    //return $this->dameResultadosHoyLapr();
                    break;
                case "bonoloto":
                    //return $this->dameResultadosHoyBono();
                    break;
                case "gordo_primitiva":
                    //return $this->dameResultadosHoyElgr();
                    break;
                case "quiniela":
                    return $this->dameProximoLaqu();
                    break;
                case "quinigol":
                    return $this->dameProximoQgol();
                    break;
                case "lototurf":
                    //return $this->dameResultadosHoyLotu();
                    break;
                case "quintuple_plus":
                    //return $this->dameResultadosHoyQupl();
                    break;
            }
        } 
    }
    private function dameProximoQgol()
    {
        $sorteos = $this->connection->query("SELECT s.*,jor.field_jornada_value,tem.field_temporada_value, par.field_partidos_value FROM sorteo s 
                                 LEFT JOIN sorteo__field_jornada jor ON s.id = jor.entity_id
                                 LEFT JOIN sorteo__field_temporada tem ON s.id = tem.entity_id
                                 LEFT JOIN sorteo__field_partidos par ON s.id = par.entity_id WHERE type = 'quinigol' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }

    private function dameProximoLaqu()
    {

        $sorteos = $this->connection->query("SELECT s.*,jor.field_jornada_value,tem.field_temporada_value, par.field_partidos_value FROM sorteo s 
                                 LEFT JOIN sorteo__field_jornada jor ON s.id = jor.entity_id
                                 LEFT JOIN sorteo__field_temporada tem ON s.id = tem.entity_id
                                 LEFT JOIN sorteo__field_partidos par ON s.id = par.entity_id WHERE type = 'quiniela' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }
}
