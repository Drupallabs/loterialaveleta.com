<?php

namespace Drupal\resultados\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Core\Config\ConfigFactory;

/**
 * Connect with selae and check
 *
 * @package Drupal\resultados\Services
 */
class ResultadosConnection
{

  protected $config  = NULL;
  protected $method = 'GET';

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  public function __construct(ConfigFactory $configFactory)
  {
    $this->configFactory = $configFactory;
  }

  public function getResultadosJuego($gameid)
  {
    if (!$gameid) {
      return null;
    } else
      return $this->queryEndpoint($gameid);
  }

  public function getPremioDecimoWeb($idsorteo)
  {
    if (!$idsorteo) {
      return null;
    } else
      return $this->queryEndpoint2($idsorteo);
  }

  public function queryEndpoint($gameid)
  {
    $config = $this->configFactory->get('resultados.configuration');
    try {
      $url = $config->get('url') . $gameid;
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

  public function queryEndpoint2($idsorteo)
  {
    $config = $this->configFactory->get('resultados.configuration');
    try {
      $url = $config->get('url') . $idsorteo;
      $response = $this->callEndpoint($url);

      return json_decode($response->getBody());
    } catch (\Exception $e) {
      dump($e);
      echo "eroror comprobando decimo en selae";
    }
  }

  public function callEndpoint($url)
  {
    $headers = array();
    $client  = new GuzzleClient();
    $request = new GuzzleRequest($this->method, $url);
    $send = $client->send($request, ['timeout' => 30]);
    return $send;
  }
}
