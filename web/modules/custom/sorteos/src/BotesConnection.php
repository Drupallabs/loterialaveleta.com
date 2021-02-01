<?php
namespace Drupal\sorteos;
use Drupal\Core\Url;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Core\Datetime\DrupalDateTime();
/**
 * Class ResultadosConnection
 *
 * @package Drupal\resultados
 */
class SorteosConnection {

  protected $config  = NULL;
  protected $url = 'https://www.loteriasyapuestas.es/servicios/fechav3/';
  protected $method = 'GET';
  protected $hoy;

  public function __construct() {
    $this->hoy = new DrupalDateTime();
  }

  public function getSorteo($gameid) {
    if(!$gameid) {
      return null;
    } else{
      switch ($gameid) {
          case "LNAC":
              break;
          case "EMIL":
              return $this->getEmil();
              break;
          case "LAPR":
            break;
          case "BONO":
            break;
          case "ELGR":
            break;
          case "LAQU":
            break;
          case "QGOL":
            break;
          case "LOTU":
            break;
          case "QUPL":
            break;
      }

    }
  }

  private function getEmil() {
    $fecha = '20191206';
    $param = '?game_id=EMIL';
    $param2 = '&fecha_sorteo='.$fecha;
    $urlfinal = $this->url.$param.$param2;
    return $this->queryEndpoint($urlfinal);
  }

  public function queryEndpoint($urlfinal) {
    try {
      $response = $this->callEndpoint($urlfinal);
      return json_decode($response->getBody());
    } catch (\Exception $e) {
    //ump($e);
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
