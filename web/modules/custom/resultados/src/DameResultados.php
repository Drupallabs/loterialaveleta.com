<?php
namespace Drupal\resultados;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Clase que obtiene los resultados de los sorteos que hay en bbdd de los dias anteriores
 */
class DameResultados
{

   private $hoy;
   private $resultados;
   private $date_format;

   public function __construct()
   {

      $this->date_format = 'Y-m-d H:i:s';
      $this->hoy = new DrupalDateTime();

      $this->resultados = [];
   }
}