<?php

namespace Drupal\resultados\Services;

use Drupal\resultados\Services\ResultadosConnection;


class ComprobarDecimoLnac
{
    /**
     * @var \Drupal\resultados\Services\ResultadosConnection
     */
    protected $resultados_connection;

    /**
     * Constructs a new object.
     *
     * @param \Drupal\resultados\Services\ResultadosConnection $resultados_connection
     *   
     */
    public function __construct(ResultadosConnection $resultados_connection)
    {
        $this->resultadosConnection = $resultados_connection;
    }

    public function comprobarDecimoSorteo($numero, $sorteo)
    {
        $comprobacion = $this->resultadosConnection->getPremioDecimoWeb($sorteo);
        $tiene_premio = false;
        foreach ($comprobacion->compruebe as $key => $value) {
            $decimocomp = (string)$value->decimo;
            $decimocomp = substr($decimocomp, 1); // quitamos el primer cero
            $resultadoc = similar_text($decimocomp, $this->decimo);
            if ($resultadoc == 5) {
                $tiene_premio = true;
                $premio = (int)$value->prize / 100;
            }
        }
        if ($tiene_premio) {
            dump($premio);
        } else {
            dump('no tiene premio');
        }
        die;
    }
}
