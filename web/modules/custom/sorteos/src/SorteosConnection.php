<?php

namespace Drupal\sorteos;

use Drupal\Core\Url;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Class SorteosConnection
 *
 * @package Drupal\sorteos
 */
class SorteosConnection
{

  protected $config  = NULL;
  protected $url = 'https://www.loteriasyapuestas.es/servicios/fechav3/';
  protected $method = 'GET';
  protected $hoy;
  protected $ayer;
  protected $ayerdia;
  protected $taxonomias = [
    18 => 'BONO',
    21 => 'ELGR',
    17 => 'EMIL',
    19 => 'LAPR',
    20 => 'LAQU',
    16 => 'LNAC',
    23 => 'LOTU',
    22 => 'QGOL',
    24 => 'QUPL'
  ];

  public function __construct()
  {
    $atras = '1';
    $this->ayer = date("Ymd", strtotime('-' . $atras . ' days'));
    $this->ayerdia = date("N", strtotime('-' . $atras . ' days'));
    $this->hoy = date("N");
  }

  public function checkSorteos()
  { // sacamos los sorteos de un dia anterior al que estamos
    switch ($this->ayerdia) {
      case "1": //LUNES
        $this->checkSorteosLunes($this->ayer);
        break;
      case "2": //MARTES
        $this->checkSorteosMartes($this->ayer);
        break;
      case "3": // MIERCOLES
        $this->checkSorteosMiercoles($this->ayer);
        break;
      case "4": //JUEVES
        $this->checkSorteosJueves($this->ayer);
        break;
      case "5":  //VIERNES
        $this->checkSorteosViernes($this->ayer);
        break;
      case "6": //SABADO
        $this->checkSorteosSabado($this->ayer);
        break;
      case "7": //DOMINGO
        $this->checkSorteosDomingo($this->ayer);
        break;
    }
  }

  public function checkSorteosLNAC()
  { // sacamos los sorteos de 4 semanas futuras de LNAC
 
    $jueves = date("Ymd", strtotime('+ 10 days')); //31
    $sorteo = $this->dameSorteo('LNAC', $jueves);

    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 16, 'Loteria Nacional');

    $sabado = date("Ymd", strtotime('+ 12 days'));

    $sorteo = $this->dameSorteo('LNAC', $sabado);

    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 16, 'Loteria Nacional');
  }

  private function checkSorteosLunes($ayer)
  {
    // Solo Bonoloto
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');
    $sorteo = $this->dameSorteo('LNAC', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 16, 'Loteria Nacional');
  }

  private function checkSorteosMartes($ayer)
  {
    // Bonoloto y Euromillones
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');
    $sorteo = $this->dameSorteo('EMIL', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 17, 'Euromillones');
    $sorteo = $this->dameSorteo('LAQU', $ayer); //  A veces hay quiniela los martes
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 20, 'La Quiniela');
    $sorteo = $this->dameSorteo('QGOL', $ayer); //  A veces hay quinigol los martes
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 22, 'Quinigol');
  }
  private function checkSorteosMiercoles($ayer)
  {
    // Solo Bonoloto
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');

    $sorteo = $this->dameSorteo('LAQU', $ayer); //  A veces hay quiniela los miercoles
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 20, 'La Quiniela');

    $sorteo = $this->dameSorteo('QGOL', $ayer); //  A veces hay quinigol los miercoles
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 22, 'Quinigol');
  }

  private function checkSorteosJueves($ayer)
  {
    // Bonoloto, Loteria Nacional, Primitiva
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');
    $sorteo = $this->dameSorteo('LNAC', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 16, 'Loteria Nacional');
    $sorteo = $this->dameSorteo('LAPR', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 19, 'Primitiva');
  }

  private function checkSorteosViernes($ayer)
  {
    // Bonoloto, Euromillones
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');
    $sorteo = $this->dameSorteo('EMIL', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 17, 'Euromillones');
  }

  private function checkSorteosSabado($ayer)
  {
    // Bonoloto, Loteria Nacional, Primitiva
    $sorteo = $this->dameSorteo('BONO', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 18, 'Bonoloto');
    $sorteo = $this->dameSorteo('LNAC', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 16, 'Loteria Nacional');
    $sorteo = $this->dameSorteo('LAPR', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 19, 'Primitiva');
  }

  private function checkSorteosDomingo($ayer)
  {
    // Bonoloto, El gordo, la quiniela, lototurf, quinigol, quintuple plus
    $sorteo = $this->dameSorteo('ELGR', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 21, 'El Gordo de La Primitiva');
    $sorteo = $this->dameSorteo('LAQU', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 20, 'La Quiniela');
    $sorteo = $this->dameSorteo('LOTU', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 23, 'Lototurf');
    $sorteo = $this->dameSorteo('QGOL', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 22, 'Quinigol');
    $sorteo = $this->dameSorteo('QUPL', $ayer);
    if (!is_string($sorteo))
      $this->guardaSorteo($sorteo, 24, 'Quintuple Plus');
  }

  private function guardaSorteo($sorteo, $gameterm, $nombre)
  {
    // si existe ya el sorteo en bbdd no hacemos nada
    $nids = \Drupal::entityQuery('node')->condition('field_id_sorteo', $sorteo[0]->id_sorteo)->execute();

    if (empty($nids)) {
      (property_exists($sorteo[0], 'num_sorteo')) ? $num_sorteo = $sorteo[0]->num_sorteo : $num_sorteo = 0;
      if ($gameterm === 16) {
        (property_exists($sorteo[0], 'nombre') && $sorteo[0]->nombre != 'SORTEO DEL JUEVES') ? $title =  ucwords(mb_strtolower(trim(preg_replace('/\s\s+/', ' ', $sorteo[0]->nombre)))) :  $title = 'Sorteo Loteria Nacional ';
      } else {
        $title = 'Sorteo ' . $nombre . ',';
      }
      $dtime = DateTimePlus::createFromFormat('Y-m-d H:i:s', $sorteo[0]->fecha_sorteo);
      $dtimeFormat = $dtime->format('d/m/Y');

      $dtime->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
      $dtimeFormat2 = $dtime->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);

      $titulo = $title . ' ' . ucfirst($sorteo[0]->dia_semana) . ' ' . $dtimeFormat;
      if ($gameterm === 16) { //Si es loteria nacional guadamos la foto del decimo
        $urldec = 'https://www.loteriasyapuestas.es/f/loterias/imagenes/estaticos/capillas/s' . $sorteo[0]->num_sorteo . '_' . $sorteo[0]->anyo . '_pc.png';
        $urllista = 'https://www.loteriasyapuestas.es/f/loterias/documentos/Loter%C3%ADa%20Nacional/listas%20de%20premios/SM_LISTAOFICIAL.A' . $sorteo[0]->anyo . '.S' . sprintf('%03d', $sorteo[0]->num_sorteo) . '.pdf';

        $file = $this->createFileDecimo($urldec, $titulo);
        /*if ($this->createFileLista($urllista, 'lnac', $titulo)) {
          $filearr2 = ['field_lista_oficial' => [
            'target_id' => $file2->id(),
            'alt' => $titulo,
            'title' => $titulo
          ]];
        }*/
        if ($this->createFileDecimo($urldec, $titulo)) {
          $filearr = ['field_decimo_sorteo' => [
            'target_id' => $file->id(),
            'alt' => $titulo,
            'title' => $titulo
          ]];
        }
      }
      //dump($dtime2); die;
      $data = [
        'title'      => $titulo,
        'type'       => 'sorteo',
        'langcode' => 'es', 'uid' => 1,
        'created' => \Drupal::time()->getRequestTime(),
        'field_selae' => json_encode($sorteo, JSON_UNESCAPED_UNICODE),
        'field_fecha_sorteo' => $dtimeFormat2,
        'field_sorteo'  => $gameterm,
        'field_num_sorteo' => $num_sorteo,
        'field_ano_sorteo' => $sorteo[0]->anyo,
        'field_id_sorteo'  => $sorteo[0]->id_sorteo,
        'field_cdc_sorteo'  => $sorteo[0]->cdc,
        'ficrontaeld_game_id'  => $sorteo[0]->game_id
      ];
      if ($gameterm === 16) {
        if ($filearr)
          if (is_array($filearr)) $data = array_merge($data, $filearr);
        if ($filearr2)
          if (is_array($filearr2)) $data = array_merge($data, $filearr2);
      }
      try {
        $node = \Drupal::entityTypeManager()->getStorage('node')->create($data);
        $node->save();
      } catch (\Exception $e) {
        dump($e);
        die;
        //  watchdog_exception('sorteosConnection', $e);
      }
    } else { // Si el sorteo ya existe actualizamos datos solamente, el campo selae
      $nid = array_values($nids);
      $node = Node::load($nid[0]);

      if ($node instanceof Node) {
        try {
          $node->set('field_selae', json_encode($sorteo, JSON_UNESCAPED_UNICODE));
          $node->save();
          drupal_set_message('El sorteo: ' . $sorteo[0]->id_sorteo . ' actualizado en bbdd ' . $nid[0]);
        } catch (\Exception $e) {
          watchdog_exception('myerrorid', $e);
        }
      }
    }
  }

  public function guardaSorteoEntity($sorteo, $gameterm, $nombre)
  {
  }

  private function dameSorteo($gameid, $fecha)
  {
    $param = '?game_id=' . $gameid;
    $param2 = '&fecha_sorteo=' . $fecha;
    $urlfinal = $this->url . $param . $param2;
    //echo $urlfinal. "<br>";
    $res = $this->queryEndpoint($urlfinal);
    if (is_string($res)) {
      drupal_set_message($res);
    }
    return $res;
  }
  public function queryEndpoint($urlfinal)
  {
    try {
      $response = $this->callEndpoint($urlfinal);
      return json_decode($response->getBody());
    } catch (\Exception $e) {
      dump($e);
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

  private function createFileDecimo($urldec, $titulo)
  {
    if (!$filecontent = file_get_contents($urldec)) {
      return false;
    } else {
      $filename = basename($urldec);
      $file = File::create([
        'uid' => 1,
        'filename' => $filename,
        'uri' => 'public://decimos/' . $filename,
        'status' => 1,
      ]);
      $file->save();
      $dir = dirname($file->getFileUri());
      if (!file_exists($dir)) {
        mkdir($dir, 0770, TRUE);
      }
      file_put_contents($file->getFileUri(), $filecontent);
      $file->save();
      $file_usage = \Drupal::service('file.usage');
      $file_usage->add($file, 'sorteos', 'user', 1);
      $file->save();
      return $file;
    }
  }

  private function createFileLista($urldec, $carpeta, $titulo)
  {
    if (!$filecontent = file_get_contents($urldec)) {
      return false;
    } else {
      $filename = basename($urldec);
      $file = File::create([
        'uid' => 1,
        'filename' => $filename,
        'uri' => 'public://listas-oficiales/' . $carpeta . '/' . $filename,
        'status' => 1,
      ]);
      $file->save();
      $dir = dirname($file->getFileUri());
      if (!file_exists($dir)) {
        mkdir($dir, 0770, TRUE);
      }
      file_put_contents($file->getFileUri(), $filecontent);
      $file->save();
      $file_usage = \Drupal::service('file.usage');
      $file_usage->add($file, 'sorteos', 'user', 1);
      $file->save();

      return $file;
    }
  }
}
