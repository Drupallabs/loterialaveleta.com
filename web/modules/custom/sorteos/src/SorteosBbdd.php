<?php

namespace Drupal\sorteos;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
//use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
//use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;

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
        $this->ahora = date('Y-m-dTH:i:s');
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
                    return $this->dameProximoLnac();
                    break;
                case "euromillones":
                    return $this->dameProximoEmil();
                    break;
                case "primitiva":
                    return $this->dameProximoLapr();
                    break;
                case "bonoloto":
                    return $this->dameProximoBono();
                    break;
                case "gordo_primitiva":
                    return $this->dameProximoElgr();
                    break;
                case "quiniela":
                    return $this->dameProximoLaqu();
                    break;
                case "quinigol":
                    return $this->dameProximoQgol();
                    break;
                case "lototurf":
                    return $this->dameProximoLotu();
                    break;
                case "quintuple_plus":
                    return $this->dameProximoQupl();
                    break;
            }
        }
    }

    /* Obtiene el proximo Loteria Nacional */
    private function dameProximoLnac()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'loteria_nacional' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }

    /* Obtiene el proximo Euromillones */
    private function dameProximoEmil()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'euromillones' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }

    /* Obtiene el proximo Primitiva */
    private function dameProximoElgr()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'gordo_primitiva' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }

    /* Obtiene el proximo Primitiva */
    private function dameProximoLapr()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'primitiva' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }

    private function dameProximoBono()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'bonoloto' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }
    /* Obtiene el proximo Quinigol */
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
    /* Obtiene la proxima quiniela */
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

    private function dameProximoLotu()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'lototurf' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();
        $sorteo = reset($sorteos);

        return $sorteo;
    }

    private function dameProximoQupl()
    {
        $sorteos = $this->connection->query("SELECT s.* FROM sorteo s WHERE type = 'quintuple_plus' AND fecha >= :fecha ORDER by s.fecha ASC", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        $sorteo = reset($sorteos);

        return $sorteo;
    }
    /**
     * Devuelve los ultimos sorteos hasta 3 meses, 
     * solo devuelve el nombre , el codigo del sorteo y la fecha
     */
    public function ultimosSorteos($gameid)
    {
        if (!$gameid) {
            return null;
        } else {
            $bundle = $this->bundles[$gameid];
            switch ($bundle) {
                case "loteria_nacional":
                    return $this->ultimosSorteosLnac();
                    break;
                case "euromillones":
                    return $this->ultimosSorteosEmil();
                    break;
                case "primitiva":
                    return $this->ultimosSorteosLapr();
                    break;
                case "bonoloto":
                    return $this->ultimosSorteosBono();
                    break;
                case "gordo_primitiva":
                    return $this->ultimosSorteosElgr();
                    break;
                case "quiniela":
                    return $this->ultimosSorteosLaqu();
                    break;
                case "quinigol":
                    return $this->ultimosSorteosQgol();
                    break;
                case "lototurf":
                    return $this->ultimosSorteosLotu();
                    break;
                case "quintuple_plus":
                    return $this->ultimosSorteosQupl();
                    break;
            }
        }
    }

    private function ultimosSorteosLnac()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'loteria_nacional' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 30", [
            ':fecha' => $this->ahora
        ])->fetchAll();


        return $sorteos;
    }

    private function ultimosSorteosBono()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'bonoloto' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 90", [
            ':fecha' => $this->ahora
        ])->fetchAll();


        return $sorteos;
    }
    private function ultimosSorteosLapr()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'primitiva' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }
    private function ultimosSorteosElgr()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'gordo_primitiva' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }
    private function ultimosSorteosEmil()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'euromillones' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }

    private function ultimosSorteosLaqu()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'quiniela' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }

    private function ultimosSorteosQgol()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'quinigol' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }

    private function ultimosSorteosLotu()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'lototurf' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }

    private function ultimosSorteosQupl()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha FROM sorteo s 
                                            WHERE type = 'quintuple_plus' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 24", [
            ':fecha' => $this->ahora
        ])->fetchAll();

        return $sorteos;
    }

    /**
     * Devuelve el pdf de resultados de un sorteo, para la pantalla de resultados
     */
    public function dameResultadoPdf($sorteoid)
    {
        $sorteo = [];

        if ($sorteoid) {
            $sorteo = $this->connection->query("SELECT f.fid, f.filename, f.uri FROM sorteo s INNER JOIN file_managed f ON s.resultados__target_id = f.fid
                                            WHERE id = :sorteoid LIMIT 1", [
                ':sorteoid' => $sorteoid
            ])->fetchAll();
        }
        $file = File::load($sorteo[0]->fid);
        if ($file) {
            return $file->url();
        } else {
            return '';
        }
    }

    public function dameUltimoSorteoLnac()
    {
        // limit 24 porque son los ultimos 3 meses
        $sorteos = $this->connection->query("SELECT s.id, s.name, s.fecha, s.id_sorteo FROM sorteo s 
                                            WHERE type = 'loteria_nacional' AND fecha < :fecha ORDER by s.fecha DESC LIMIT 30", [
            ':fecha' => $this->ahora
        ])->fetchObject();


        return $sorteos;
    }
}
