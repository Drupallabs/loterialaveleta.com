<?php

namespace Drupal\resultados;

class ComprobarDecimo
{

  protected $decimo = null;
  protected $sorteo  = null;
  protected $api = null;
  protected $resultados = array();

  public function __construct($decimo, $sorteo, $api)
  {
    $this->decimo = $decimo;
    $this->sorteo = $sorteo;
    $this->api = $api;
  }

  public function dameResultadosComprobacion()
  {
    $imageurl = '';
    $premio = '';
    //dump($this->sorteo);
    if ($this->sorteo->field_decimo_imagen[0]) {
      $decimo_imagen = $this->sorteo->field_decimo_imagen[0]->getValue();
      $imageurl = file_create_url($this->sorteo->field_decimo_imagen[0]->entity->getFileUri());
    }
    $decimocomp = null;
    $resultadoc = null;
    $tiene_premio = false;
    foreach ($this->api->compruebe as $key => $value) {
      $decimocomp = (string)$value->decimo;
      $decimocomp = substr($decimocomp, 1); // quitamos el primer cero
      $resultadoc = similar_text($decimocomp, $this->decimo);
      if ($resultadoc == 5) {
        $tiene_premio = true;
        $premio = (int)$value->prize / 100;
      }
    }
    if ($tiene_premio) {
      return (object) [
        'tipo' => 'ok',
        'mensaje' => 'Devolviendo datos de comprobacion',
        'datos'    => (object) [
          'sorteo'    => $this->sorteo->getName(),
          'decimo'    => $this->decimo,
          'decimo_imagen_url' => $imageurl,
          'tiene_precio' => true,
          'premio' => $premio,
          'compruebe' => $decimocomp . ' ' . $resultadoc
        ],
      ];
    } else {
      return (object) [
        'tipo' => 'nook',
        'mensaje' => 'Devolviendo datos de comprobacion',
        'datos'    => (object) [
          'sorteo'    => $this->sorteo->getName(),
          'decimo'    => $this->decimo,
          'decimo_imagen_url' => $imageurl,
          'tiene_precio' => false,
          //'compruebe' => $decimocomp. ' '.$resultadoc
        ],
      ];
    }
  }
  /* devuele el premio si lo tiene y false sino hay premio */
  public function dameResultadosComprobacionLight()
  {
    $decimocomp = null;
    $resultadoc = null;
    $tiene_premio = false;
    $premio = '';

    foreach ($this->api->compruebe as $key => $value) {
      $decimocomp = (string)$value->decimo;
      $decimocomp = substr($decimocomp, 1); // quitamos el primer cero

      $resultadoc = similar_text($decimocomp, $this->decimo);
      //dump($resultadoc);
      if ($resultadoc == 5) {
        $tiene_premio = true;
        $premio = (int)$value->prize / 100;
      }
      if ($tiene_premio) {
        return $premio;
      } else {
        return false;
      }
    }
  }
}
