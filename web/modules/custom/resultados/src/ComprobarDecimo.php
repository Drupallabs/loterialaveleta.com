<?php
namespace Drupal\resultados;
use Drupal\resultados\ResultadosConnection;

class ComprobarDecimo {

  protected $decimo = null;
  protected $nodesorteo  = null;
  protected $api = null;
  protected $resultados = array();

  public function __construct($decimo,$nodesorteo, $api) {
    $this->decimo = $decimo;
    $this->nodesorteo = $nodesorteo;
    $this->api = $api;
  }

  public function dameResultadosComprobacion() {
      $imageurl = '';
      $decimo_imagen = ''; $premio = '';
      if ($this->nodesorteo->field_decimo_sorteo[0]){
        $decimo_imagen = $this->nodesorteo->field_decimo_sorteo[0]->getValue();
        $imageurl = file_create_url($this->nodesorteo->field_decimo_sorteo[0]->entity->getFileUri());
      }
      $decimocomp = null; $resultadoc = null; $tiene_premio = false;
       foreach($this->api->compruebe as $key => $value ){
         $decimocomp = (string)$value->decimo;
         $decimocomp = substr($decimocomp,1); // quitamos el primer cero
         $resultadoc = similar_text($decimocomp,$this->decimo);
         if($resultadoc == 5) {
           $tiene_premio = true; $premio = (integer)$value->prize/100;
         }
       }
       if($tiene_premio) {
            return (object) [
              'tipo' => 'ok',
              'mensaje' => 'Devolviendo datos de comprobacion',
              'datos'    => (object) [
                'sorteo'    => $this->nodesorteo->getTitle(),
                'decimo'    => $this->decimo,
              //  'decimo_imagen' => $decimo_imagen,
                'decimo_imagen_url' => $imageurl,
                'tiene_precio' => true,
                'premio' => $premio,
                'compruebe' => $decimocomp. ' '.$resultadoc
              ],
            ];
        } else {
          return (object) [
            'tipo' => 'nook',
            'mensaje' => 'Devolviendo datos de comprobacion',
            'datos'    => (object) [
              'sorteo'    => $this->nodesorteo->getTitle(),
              'decimo'    => $this->decimo,
              //'decimo_imagen' => $decimo_imagen,
              'decimo_imagen_url' => $imageurl,
              'tiene_precio' => false,
              //'compruebe' => $decimocomp. ' '.$resultadoc
            ],
          ];
        }
  }
}
