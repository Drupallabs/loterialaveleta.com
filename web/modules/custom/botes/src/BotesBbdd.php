<?php

namespace Drupal\botes;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Database\Connection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
//use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;

/**
 * Clase que obtiene los botes de los sorteos que hay en bbdd de los dias futuros
 */
class BotesBbdd
{
    private $date_format;
    private $bote;
    private $url = 'https://www.loteriasyapuestas.es/servicios/sorteov3/?';
    private $url2 = 'https://www.loteriasyapuestas.es/servicios/fechav3/?';
    private $method = 'GET';

    /**
     * The db connection.
     * 
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->date_format = 'Y-m-dTH:i:s.uZ';
        $this->hoy = new DrupalDateTime();
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
        //$current_time = DrupalDateTime::createFromTimestamp(time());
        //$ahora = $current_time->format(DateTimeItem::DATETIME_STORAGE_FORMAT);
        $ahora = date('Y-m-dT00:00:00');

        $sorteos = $this->connection->query("SELECT s.id_sorteo, s.type, s.fecha FROM sorteo s WHERE type = :bundle AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':bundle' => $bundle, ':fecha' => $ahora
        ])->fetchAll();
 
        $sorteo = reset($sorteos);
        if ($sorteo == null) {
            return $this->bote;
        } else {
            $fe = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);

            $param = 'game_id=' . $gameid;
            $param2 = '&fecha_sorteo=' .  $fe->format('Ymd');
            $urlfinal = $this->url2 . $param . $param2;
            $sorteo2 = $this->queryEndpoint($urlfinal);
            return $this->filtraDatos($sorteo2);
        }
    }

    private function filtraDatos($sorteo)
    {
        if ($sorteo[0]->premio_bote == 0) {
            $premio = null;
        } else {
            $premio = $sorteo[0]->premio_bote;
        }
        return (object) [
            'fecha_sorteo' => $sorteo[0]->fecha_sorteo,
            'tenthPrice' => $sorteo[0]->tenthPrice,
            'dia_semana' => $sorteo[0]->dia_semana,
            'premio_bote' => $premio,
            'id_sorteo' => $sorteo[0]->id_sorteo,
        ];
    }

    private function queryEndpoint($urlfinal)
    {
        try {
            $response = $this->callEndpoint($urlfinal);
            return json_decode($response->getBody());
        } catch (\Exception $e) {
            dump($e);
            //  watchdog_exception('resultados', $e);
        }
    }
    public function callEndpoint($url)
    {
        //$headers = array();
        $client  = new GuzzleClient();
        $request = new GuzzleRequest($this->method, $url);
        $send = $client->send($request, ['timeout' => 30]);
        return $send;
    }
}
