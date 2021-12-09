<?php

namespace Drupal\resultados;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;

/**
 * Clase que obtiene los resultados de los sorteos que hay en bbdd de los dias anteriores
 */
class ResultadosBbdd
{

   private $hoy;
   private $resultados;
   private $date_format;

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
      $this->resultados = [];
   }

   public static function create(ContainerInterface $container)
   {
      return new static($container->get('database'));
   }

   public function getResultadosJuego($gameid, $cuantos)
   {
      if (!$gameid) {
         return null;
      } else {
         switch ($gameid) {
            case "loteria_nacional":
               return $this->dameResultadosHoyLnac($cuantos);
               break;
            case "euromillones":
               return $this->dameResultadosHoyEmil($cuantos);
               break;
            case "primitiva":
               return $this->dameResultadosHoyLapr($cuantos);
               break;
            case "bonoloto":
               return $this->dameResultadosHoyBono($cuantos);
               break;
            case "gordo_primitiva":
               return $this->dameResultadosHoyElgr($cuantos);
               break;
            case "quiniela":
               return $this->dameResultadosHoyLaqu($cuantos);
               break;
            case "quinigol":
               return $this->dameResultadosHoyQgol($cuantos);
               break;
            case "lototurf":
               return $this->dameResultadosHoyLotu($cuantos);
               break;
            case "quintuple_plus":
               return $this->dameResultadosHoyQupl($cuantos);
               break;
         }
      }
   }

   private function dameResultadosHoyLnac($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,p1.field_primer_premio_value,p2.field_segundo_premio_value,fm.uri FROM sorteo s
                           LEFT JOIN sorteo__field_primer_premio p1 ON s.id = p1.entity_id
                           LEFT JOIN sorteo__field_segundo_premio p2 ON s.id = p2.entity_id
                           LEFT JOIN sorteo__field_decimo_imagen di ON s.id = di.entity_id
                           LEFT JOIN file_managed fm ON di.field_decimo_imagen_target_id = fm.fid  WHERE s.type = :bundle AND s.fecha < :ahora ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'loteria_nacional',
            ':ahora' => $this->ahora
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         //dump($sorteo);
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['nombre'] = $sorteo->name;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['primerpremio'] = sprintf("%05s", $sorteo->field_primer_premio_value);
         $res['segundopremio'] = sprintf("%05s", $sorteo->field_segundo_premio_value);
         $res['decimo'] = $sorteo->uri;
         $res['decimo_url'] = file_create_url($sorteo->uri);
         array_push($resultado, $res);
      }
      return $resultado;
   }
   private function dameResultadosHoyEmil($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      //$cuantos = 2; devolvemos siempre 10 de momento
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_euromillones f ON f.entity_id = s.id WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'euromillones',
            ':ahora' => $this->ahora
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = (int)$dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['dia'] = $dtime->format('d');
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_euromillones_bola1;
         $res['bola2'] = $sorteo->field_combinacion_euromillones_bola2;
         $res['bola3'] = $sorteo->field_combinacion_euromillones_bola3;
         $res['bola4'] = $sorteo->field_combinacion_euromillones_bola4;
         $res['bola5'] = $sorteo->field_combinacion_euromillones_bola5;
         $res['estrella1'] = $sorteo->field_combinacion_euromillones_estrella1;
         $res['estrella2'] = $sorteo->field_combinacion_euromillones_estrella2;
         array_push($resultado, $res);
      }

      return $resultado;
   }

   private function dameResultadosHoyLapr($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.*,j.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_primitiva f ON f.entity_id = s.id 
                       LEFT JOIN sorteo__field_joker j ON j.entity_id = s.id WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'primitiva',
            ':ahora' => $this->ahora
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_primitiva_bola1;
         $res['bola2'] = $sorteo->field_combinacion_primitiva_bola2;
         $res['bola3'] = $sorteo->field_combinacion_primitiva_bola3;
         $res['bola4'] = $sorteo->field_combinacion_primitiva_bola4;
         $res['bola5'] = $sorteo->field_combinacion_primitiva_bola5;
         $res['bola6'] = $sorteo->field_combinacion_primitiva_bola6;
         $res['reintegro'] = $sorteo->field_combinacion_primitiva_reintegro;
         $res['complementario'] = $sorteo->field_combinacion_primitiva_complementario;
         $res['joker'] = $sorteo->field_joker_value;
         array_push($resultado, $res);
      }

      return $resultado;
   }

   private function dameResultadosHoyBono($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_bonoloto f ON f.entity_id = s.id 
                       WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'bonoloto',
            ':ahora' => $this->ahora,
            //':limit' => $cuantos
         ]
      )->fetchAll();
      $resultado = [];

      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_bonoloto_bola1;
         $res['bola2'] = $sorteo->field_combinacion_bonoloto_bola2;
         $res['bola3'] = $sorteo->field_combinacion_bonoloto_bola3;
         $res['bola4'] = $sorteo->field_combinacion_bonoloto_bola4;
         $res['bola5'] = $sorteo->field_combinacion_bonoloto_bola5;
         $res['bola6'] = $sorteo->field_combinacion_bonoloto_bola6;
         $res['reintegro'] = $sorteo->field_combinacion_bonoloto_reintegro;
         $res['complementario'] = $sorteo->field_combinacion_bonoloto_complementario;
         array_push($resultado, $res);
      }

      return $resultado;
   }

   private function dameResultadosHoyElgr($cuantos)
   {

      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_gordo f ON f.entity_id = s.id WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'gordo_primitiva',
            ':ahora' => $this->ahora,
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_gordo_bola1;
         $res['bola2'] = $sorteo->field_combinacion_gordo_bola2;
         $res['bola3'] = $sorteo->field_combinacion_gordo_bola3;
         $res['bola4'] = $sorteo->field_combinacion_gordo_bola4;
         $res['bola5'] = $sorteo->field_combinacion_gordo_bola5;
         $res['clave'] = $sorteo->field_combinacion_gordo_clave;
         array_push($resultado, $res);
      }

      return $resultado;
   }

   private function dameResultadosHoyLaqu($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,jor.field_jornada_value,tem.field_temporada_value, par.field_partidos_value FROM sorteo s 
                                 LEFT JOIN sorteo__field_jornada jor ON s.id = jor.entity_id
                                 LEFT JOIN sorteo__field_temporada tem ON s.id = tem.entity_id
                                 LEFT JOIN sorteo__field_partidos par ON s.id = par.entity_id
                                 WHERE s.type = :bundle AND s.fecha < :ahora ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'quiniela',
            ':ahora' => $this->ahora
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['partidos'] = $sorteo->field_partidos_value;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['jornada'] = $sorteo->field_jornada_value;
         $res['temporada'] = $sorteo->field_temporada_value;
         array_push($resultado, $res);
      }
      return $resultado;
   }

   private function dameResultadosHoyQgol($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,jor.field_jornada_value,tem.field_temporada_value, par.field_partidos_value FROM sorteo s 
                                 LEFT JOIN sorteo__field_jornada jor ON s.id = jor.entity_id
                                 LEFT JOIN sorteo__field_temporada tem ON s.id = tem.entity_id
                                 LEFT JOIN sorteo__field_partidos par ON s.id = par.entity_id
                                 WHERE s.type = :bundle AND s.fecha < :ahora ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'quinigol',
            ':ahora' => $this->ahora,
         ]
      )->fetchAll();
      $resultado = [];

      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['partidos'] = $sorteo->field_partidos_value;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['jornada'] = $sorteo->field_jornada_value;
         $res['temporada'] = $sorteo->field_temporada_value;
         array_push($resultado, $res);
      }
      return $resultado;
   }
   private function dameResultadosHoyLotu($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_lototurf f ON f.entity_id = s.id WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'lototurf',
            ':ahora' => $this->ahora
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_lototurf_bola1;
         $res['bola2'] = $sorteo->field_combinacion_lototurf_bola2;
         $res['bola3'] = $sorteo->field_combinacion_lototurf_bola3;
         $res['bola4'] = $sorteo->field_combinacion_lototurf_bola4;
         $res['bola5'] = $sorteo->field_combinacion_lototurf_bola5;
         $res['bola6'] = $sorteo->field_combinacion_lototurf_bola6;
         $res['reintegro'] = $sorteo->field_combinacion_lototurf_reintegro;
         $res['caballo'] = $sorteo->field_combinacion_lototurf_caballo;
         array_push($resultado, $res);
      }
      return $resultado;
   }

   private function dameResultadosHoyQupl($cuantos)
   {
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteos = $this->connection->queryRange(
         "SELECT s.*,f.* FROM sorteo s LEFT JOIN sorteo__field_combinacion_quintuple f ON f.entity_id = s.id WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         0,
         $cuantos,
         [
            ':bundle' => 'quintuple_plus',
            ':ahora' => $this->ahora,
         ]
      )->fetchAll();
      $resultado = [];
      foreach ($sorteos as $key => $sorteo) {
         $dtime = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->fecha);
         $res['dia'] = $dtime->format('d');
         $res['fecha_sorteo'] = $sorteo->fecha;
         $res['dia_semana'] = $sorteo->dia_semana;
         $res['escrutinio'] = $sorteo->escrutinio;
         $res['apuestas'] = $sorteo->apuestas;
         $res['recaudacion'] = $sorteo->recaudacion;
         $res['premio_bote'] = $sorteo->premio_bote;
         $res['premios'] = $sorteo->premios;
         $res['bola1'] = $sorteo->field_combinacion_quintuple_bola1;
         $res['bola2'] = $sorteo->field_combinacion_quintuple_bola2;
         $res['bola3'] = $sorteo->field_combinacion_quintuple_bola3;
         $res['bola4'] = $sorteo->field_combinacion_quintuple_bola4;
         $res['bola5'] = $sorteo->field_combinacion_quintuple_bola5;
         $res['bola6'] = $sorteo->field_combinacion_quintuple_bola6;
         array_push($resultado, $res);
      }
      return $resultado;
   }
   /**
    * Obtiene el proximos sorteo futuro que tiene bote a partir de ahora
    */
   private function querySorteos($bundle, $cuantos)
   {
      // SELECT s.* FROM sorteo s WHERE s.type = 'euromillones' AND s.fecha < '2020-06-09T10:00:56' AND s.apuestas != 0 ORDER BY s.fecha DESC
      // en Base de datos sacamos cual es el proximo sorteo.
      $current_time = DrupalDateTime::createFromTimestamp(time());
      $ahora = $current_time->format(DateTimeItem::DATETIME_STORAGE_FORMAT);
      if (!$cuantos) {
         $cuantos = 10;
      }
      $sorteo = $this->connection->query(
         "SELECT s.* FROM sorteo s WHERE s.type = :bundle AND s.fecha < :ahora AND s.apuestas != 0 ORDER BY s.fecha DESC",
         [
            ':bundle' => $bundle,
            ':ahora' => $ahora,
            //':cuantos' => $cuantos
         ]
      )->fetchAll();
      return $sorteo;
   }
   /**
    * Obtiene el proximos sorteo futuro que tiene bote a partir de ahora
    */
   private function querySorteosLNAC($bundle, $cuantos)
   {
      // en Base de datos sacamos cual es el proximo sorteo.
      $ahora = date($this->date_format);

      $sorteo = $this->connection->query("SELECT s.*, pd.field_precio_decimo_value FROM sorteo s LEFT JOIN sorteo__field_precio_decimo pd ON s.id=pd.entity_id WHERE type = :bundle AND fecha > :fecha", [
         ':bundle' => $bundle, ':fecha' <= $ahora
      ])->fetchObject();
      return $sorteo;
   }
}
