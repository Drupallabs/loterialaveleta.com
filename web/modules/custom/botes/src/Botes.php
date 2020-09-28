<?php

/*** SERVICIO QUE ME DA LOS BOTES */

namespace Drupal\botes;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Datetime\DateTimePlus;

class Botes
{

  protected $config  = NULL;
  protected $url = 'https://www.loteriasyapuestas.es/servicios/fechav3/';
  protected $method = 'GET';
  protected $hoy;
  protected $hoydia;
  private $format;
  private $date_str;
  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Botes constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory)
  {
    $this->configFactory = $config_factory;
    $config = $this->configFactory->get('botes.config_form');
    $url = $config->get('url');
    if ($url != "") {
      $this->url = $url;
    }
    $this->format = 'Ymd';

    $this->date_str = date('Ymd'); // poner esta linea cuando pase todo
    $this->hoydia = date("N");  // poner esto cuando pase todo
    //$this->hoydia = "1";
    //$this->date_str = '20200302'; // Se queda congelado el dia 2 de marzo lunes
  }

  public function getBote($gameid)
  {
    if (!$gameid) {
      return null;
    } else {
      switch ($gameid) {
        case "LNAC":
          return $this->dameBoteHoyLnac();
          break;
        case "EMIL":
          return $this->dameBoteHoyEmil();
          break;
        case "LAPR":
          return $this->dameBoteHoyLapr();
          break;
        case "BONO":
          return $this->dameBoteHoyBono();
          break;
        case "ELGR":
          return $this->dameBoteHoyElgr();
          break;
        case "LAQU":
          return $this->dameBoteHoyLaqu();
          break;
        case "QGOL":
          return $this->dameBoteHoyQgol();
          break;
        case "LOTU":
          return $this->dameBoteHoyLotu();
          break;
        case "QUPL":
          return $this->dameBoteHoyQupl();
          break;
      }
    }
  }

  /**
   * Devuelve el bote del concurso segun el dia que estemos
   */
  private function dameBoteHoyLnac()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('LNAC', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('LNAC', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('LNAC', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('LNAC', $hoy->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('LNAC', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('LNAC', $hoy->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('LNAC', $hoy->modify('+4 days')->format('Ymd'));
        break;
    }
  }

  private function dameBoteHoyEmil()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('EMIL', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('EMIL', $hoy->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('EMIL', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('EMIL', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('EMIL', $hoy->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('EMIL', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('EMIL', $hoy->modify('+2 days')->format('Ymd'));
        break;
    }
  }

  private function dameBoteHoyLapr()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('LAPR', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('LAPR', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('LAPR', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('LAPR', $hoy->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('LAPR', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('LAPR', $hoy->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('LAPR', $hoy->modify('+4 days')->format('Ymd'));
        break;
    }
  }
  private function dameBoteHoyBono()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('BONO', $hoy->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('BONO', $hoy->modify('+1 days')->format('Ymd'));
        break;
    }
  }
  private function dameBoteHoyElgr()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('ELGR', $hoy->modify('+6 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('ELGR', $hoy->modify('+5 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('ELGR', $hoy->modify('+4 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('ELGR', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('ELGR', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('ELGR', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('ELGR', $hoy->format('Ymd'));
        break;
    }
  }
  private function dameBoteHoyLaqu()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('LAQU', $hoy->modify('+6 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('LAQU', $hoy->modify('+5 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('LAQU', $hoy->modify('+4 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('LAQU', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('LAQU', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('LAQU', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('LAQU', $hoy->format('Ymd'));
        break;
    }
  }

  private function dameBoteHoyQgol()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('QGOL', $hoy->modify('+6 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('QGOL', $hoy->modify('+5 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('QGOL', $hoy->modify('+4 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('QGOL', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('QGOL', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('QGOL', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('QGOL', $hoy->format('Ymd'));
        break;
    }
  }

  private function dameBoteHoyLotu()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('LOTU', $hoy->modify('+6 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('LOTU', $hoy->modify('+5 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('LOTU', $hoy->modify('+4 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('LOTU', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('LOTU', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('LOTU', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('LOTU', $hoy->format('Ymd'));
        break;
    }
  }

  private function dameBoteHoyQupl()
  {
    $hoy = DateTimePlus::createFromFormat($this->format, $this->date_str);
    switch ($this->hoydia) {
      case "1": //LUNES
        return $this->queryBote('QUPL', $hoy->modify('+6 days')->format('Ymd'));
        break;
      case "2": //MARTES
        return $this->queryBote('QUPL', $hoy->modify('+5 days')->format('Ymd'));
        break;
      case "3": // MIERCOLES
        return $this->queryBote('QUPL', $hoy->modify('+4 days')->format('Ymd'));
        break;
      case "4": //JUEVES
        return $this->queryBote('QUPL', $hoy->modify('+3 days')->format('Ymd'));
        break;
      case "5":  //VIERNES
        return $this->queryBote('QUPL', $hoy->modify('+2 days')->format('Ymd'));
        break;
      case "6": //SABADO
        return $this->queryBote('QUPL', $hoy->modify('+1 days')->format('Ymd'));
        break;
      case "7": //DOMINGO
        return $this->queryBote('QUPL', $hoy->format('Ymd'));
        break;
    }
  }

  private function queryBote($sorteo, $fecha)
  {
    $param = '?game_id=' . $sorteo;
    $param2 = '&fecha_sorteo=' . $fecha;
    $urlfinal = $this->url . $param . $param2;
    //echo $urlfinal . "<br>";

    $sorteo = $this->queryEndpoint($urlfinal);
    return $this->filtraDatos($sorteo);
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
      //  dump($e);
      //  watchdog_exception('resultados', $e);
      /*
      return (object) [
        'response_type' => '',
        'response_data' => [],
        'pagination'    => (object) [
          'total_count'    => 0,
          'current_limit'  => 0,
          'current_offset' => 0,
        ],
      ];*/
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
