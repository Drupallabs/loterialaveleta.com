<?php

namespace Drupal\botes;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Database\Connection;

/**
 * Clase definitiva para coger los votes
 */
class DameBotes
{

    private $date_format;
    private $bote;

    /**
     * The db connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;
    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;



    public function __construct(Connection $connection, EntityTypeManagerInterface $entity_type_manager)
    {
        $this->entityTypeManager = $entity_type_manager;
        $this->connection = $connection;
        $this->date_format = 'Y-m-dTH:i:s';
        $this->hoy = new DrupalDateTime();
        $this->botes = [];
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
        return new static($container->get('entity_type.manager'), $container->get('database'));
    }

    public function getBote($gameid)
    {
        if (!$gameid) {
            return null;
        } else {
            switch ($gameid) {
                case "LNAC":
                    return $this->queryBote('loteria_nacional');
                    break;
                case "EMIL":
                    return $this->queryBote('euromillones');
                    break;
                case "LAPR":
                    return $this->queryBote('primitiva');
                    break;
                case "BONO":
                    return $this->queryBote('bonoloto');
                    break;
                case "ELGR":
                    return $this->queryBote('gordo_primitiva');
                    break;
                case "LAQU":
                    return $this->queryBote('quiniela');
                    break;
                case "QGOL":
                    return $this->queryBote('quinigol');
                    break;
                case "LOTU":
                    return $this->queryBote('lototurf');
                    break;
                case "QUPL":
                    return $this->queryBote('quintuple_plus');
                    break;
            }
        }
    }
    /**
     * Obtiene el proximos sorteo futuro que tiene bote a partir de ahora
     */
    private function queryBote($bundle)
    {
        $ahora = date($this->date_format);
        $sorteo = $this->connection->query("SELECT s.*, pd.field_precio_decimo_value FROM sorteo s LEFT JOIN sorteo__field_precio_decimo pd ON s.id=pd.entity_id WHERE type = :bundle AND fecha > :fecha", [
            ':bundle' => $bundle, ':fecha' => $ahora
        ])->fetchObject();


        return $this->filtraDatos($sorteo);
    }

    private function filtraDatos($sorteo)
    {
        if ($sorteo) {

            if ($sorteo->premio_bote == 0) {
                $premio = null;
            } else {
                $premio = $sorteo->premio_bote;
            }
            return (object) [
                'fecha_sorteo' => $sorteo->fecha,
                'tenthPrice' => ($sorteo->field_precio_decimo_value) ? $sorteo->field_precio_decimo_value : null,
                'dia_semana' => $sorteo->dia_semana,
                'premio_bote' => $premio,
                'id_sorteo' => $sorteo->premio_bote,
            ];
        } else {
            return $this->bote;
        }
    }
}
