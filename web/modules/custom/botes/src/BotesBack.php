<?php
/*** SERVICIO QUE ME DA LOS BOTES */

namespace Drupal\botes;
use Drupal\Core\Url;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Core\Config\ConfigFactoryInterface;

class Botes {

  protected $config  = NULL;
  protected $url = 'https://www.loteriasyapuestas.es/servicios/fechav3/';
  protected $method = 'GET';
  protected $hoy;
  protected $hoydia;
  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Botes constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $config = $this->configFactory->get('botes.config_form');
    $url = $config->get('url');
      if ($url != "") {
          $this->url = $url;
        }
    $this->hoy = date("Ymd");
    $this->hoydia = date("N");
  }

  public function getBote($gameid) {

    if(!$gameid) { return null;
    } else{
      switch ($gameid) {
          case "LNAC":
            return $this->dameBoteHoyLnac();break;
          case "EMIL":
              return $this->dameBoteHoyEmil();break;
          case "LAPR":
              return $this->dameBoteHoyLapr();break;
          case "BONO":
              return $this->dameBoteHoyBono();break;
          case "ELGR":
              return $this->dameBoteHoyElgr();break;
          case "LAQU":
             return $this->dameBoteHoyLaqu();break;
          case "QGOL":
             return $this->dameBoteHoyQgol();break;
          case "LOTU":
            return $this->dameBoteHoyLotu();break;
          case "QUPL":
            return $this->dameBoteHoyQupl();break;
      }
    }
  }

  /**
  * Devuelve el bote del concurso segun el dia que estemos
  */
  private function dameBoteHoyLnac(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('LNAC',date("Ymd",strtotime('+3 days'))); break;
        case "2": //MARTES
          return $this->queryBote('LNAC',date("Ymd",strtotime('+2 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('LNAC',date("Ymd",strtotime('+1 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('LNAC',date("Ymd")); break;
        case "5":  //VIERNES
          return $this->queryBote('LNAC',date("Ymd",strtotime('+1 days'))); break;
        case "6": //SABADO
          return $this->queryBote('LNAC',date("Ymd")); break;
        case "7": //DOMINGO
          return $this->queryBote('LNAC',date("Ymd",strtotime('+4 days'))); break;
    }
  }

  private function dameBoteHoyEmil(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('EMIL',date("Ymd",strtotime('+1 days'))); break;
        case "2": //MARTES
          return $this->queryBote('EMIL',date("Ymd")); break;
        case "3": // MIERCOLES
          return $this->queryBote('EMIL',date("Ymd",strtotime('+2 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('EMIL',date("Ymd",strtotime('+1days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('EMIL',date("Ymd")); break;
        case "6": //SABADO
          return $this->queryBote('EMIL',date("Ymd",strtotime('+3 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('EMIL',date("Ymd",strtotime('+2 days'))); break;
    }
  }

  private function dameBoteHoyLapr(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('LAPR',date("Ymd",strtotime('+3 days'))); break;
        case "2": //MARTES
          return $this->queryBote('LAPR',date("Ymd",strtotime('+2 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('LAPR',date("Ymd",strtotime('+1 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('LAPR',date("Ymd")); break;
        case "5":  //VIERNES
          return $this->queryBote('LAPR',date("Ymd",strtotime('+1 days'))); break;
        case "6": //SABADO
          return $this->queryBote('LAPR',date("Ymd")); break;
        case "7": //DOMINGO
          return $this->queryBote('LAPR',date("Ymd",strtotime('+4 days'))); break;
    }
  }
  private function dameBoteHoyBono(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('BONO',date("Ymd")); break;
        case "2": //MARTES
          return $this->queryBote('BONO',date("Ymd")); break;
        case "3": // MIERCOLES
          return $this->queryBote('BONO',date("Ymd")); break;
        case "4": //JUEVES
          return $this->queryBote('BONO',date("Ymd")); break;
        case "5":  //VIERNES
          return $this->queryBote('BONO',date("Ymd")); break;
        case "6": //SABADO
          return $this->queryBote('BONO',date("Ymd")); break;
        case "7": //DOMINGO
        return $this->queryBote('BONO',date("Ymd")); break;
    }
  }
  private function dameBoteHoyElgr(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('ELGR',date("Ymd",strtotime('+6 days'))); break;
        case "2": //MARTES
          return $this->queryBote('ELGR',date("Ymd",strtotime('+5 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('ELGR',date("Ymd",strtotime('+4 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('ELGR',date("Ymd",strtotime('+3 days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('ELGR',date("Ymd",strtotime('+2 days'))); break;
        case "6": //SABADO
          return $this->queryBote('ELGR',date("Ymd",strtotime('+1 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('ELGR',date("Ymd")); break;
    }
  }
  private function dameBoteHoyLaqu(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('LAQU',date("Ymd",strtotime('+6 days'))); break;
        case "2": //MARTES
          return $this->queryBote('LAQU',date("Ymd",strtotime('+5 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('LAQU',date("Ymd",strtotime('+4 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('LAQU',date("Ymd",strtotime('+3 days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('LAQU',date("Ymd",strtotime('+2 days'))); break;
        case "6": //SABADO
          return $this->queryBote('LAQU',date("Ymd",strtotime('+1 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('LAQU',date("Ymd")); break;
    }
  }

  private function dameBoteHoyQgol(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('QGOL',date("Ymd",strtotime('+6 days'))); break;
        case "2": //MARTES
          return $this->queryBote('QGOL',date("Ymd",strtotime('+5 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('QGOL',date("Ymd",strtotime('+4 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('QGOL',date("Ymd",strtotime('+3 days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('QGOL',date("Ymd",strtotime('+2 days'))); break;
        case "6": //SABADO
          return $this->queryBote('QGOL',date("Ymd",strtotime('+1 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('QGOL',date("Ymd")); break;
    }
  }

  private function dameBoteHoyLotu(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('LOTU',date("Ymd",strtotime('+6 days'))); break;
        case "2": //MARTES
          return $this->queryBote('LOTU',date("Ymd",strtotime('+5 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('LOTU',date("Ymd",strtotime('+4 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('LOTU',date("Ymd",strtotime('+3 days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('LOTU',date("Ymd",strtotime('+2 days'))); break;
        case "6": //SABADO
          return $this->queryBote('LOTU',date("Ymd",strtotime('+1 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('LOTU',date("Ymd")); break;
    }
  }

  private function dameBoteHoyQupl(){
    switch ($this->hoydia) {
        case "1": //LUNES
          return $this->queryBote('QUPL',date("Ymd",strtotime('+6 days'))); break;
        case "2": //MARTES
          return $this->queryBote('QUPL',date("Ymd",strtotime('+5 days'))); break;
        case "3": // MIERCOLES
          return $this->queryBote('QUPL',date("Ymd",strtotime('+4 days'))); break;
        case "4": //JUEVES
          return $this->queryBote('QUPL',date("Ymd",strtotime('+3 days'))); break;
        case "5":  //VIERNES
          return $this->queryBote('QUPL',date("Ymd",strtotime('+2 days'))); break;
        case "6": //SABADO
          return $this->queryBote('QUPL',date("Ymd",strtotime('+1 days'))); break;
        case "7": //DOMINGO
          return $this->queryBote('QUPL',date("Ymd")); break;
    }
  }

  private function queryBote($sorteo,$fecha) {
    $param = '?game_id='.$sorteo;
    $param2 = '&fecha_sorteo='.$fecha;
    $urlfinal = $this->url.$param.$param2;
    //echo $urlfinal."<br>";
    return $this->queryEndpoint($urlfinal);
  }
  public function queryEndpoint($urlfinal) {
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
  public function callEndpoint($url) {
    //$headers = array();
    $client  = new GuzzleClient();
    $request = new GuzzleRequest($this->method, $url);
    $send = $client->send($request, ['timeout' => 30]);
    return $send;
  }
}
