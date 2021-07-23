<?php
namespace Drupal\resultados;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class ResultadosConnection
 *
 * @package Drupal\resultados
 */
class ResultadosConnection {

  protected $config  = NULL;
  protected $urlultimos = 'https://www.loteriasyapuestas.es/servicios/ultimosv4/?game_id=';
  protected $urldecimos = 'https://www.loteriasyapuestas.es/servicios/premioDecimoWeb/?idsorteo=';
  protected $method = 'GET';

  public function __construct() {
  }

  public function getResultadosJuego($gameid) {
    if(!$gameid) {
      return null;
    } else
      return $this->queryEndpoint($gameid);
  }
  
  public function getPremioDecimoWeb($idsorteo) {
    if(!$idsorteo) {
      return null;
    } else
      return $this->queryEndpoint2($idsorteo);
  }

  public function queryEndpoint($gameid) {
    try {
      $url = $this->urlultimos.$gameid;
      $response = $this->callEndpoint($url);
      return json_decode($response->getBody());
    } catch (\Exception $e) {
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

  public function queryEndpoint2($idsorteo) {
    try {
      $url = $this->urldecimos.$idsorteo;
      $response = $this->callEndpoint($url);
      return json_decode($response->getBody());
    } catch (\Exception $e) {
      dump($e);
      echo "eroror comprobando decimo en selae";
    }
  }

  public function callEndpoint($url) {
    $headers = array();
    $client  = new GuzzleClient();
    $request = new GuzzleRequest($this->method, $url);
    $send = $client->send($request, ['timeout' => 30]);
    return $send;
  }
}
