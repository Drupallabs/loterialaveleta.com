<?php

namespace Drupal\resultados;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Clase que obtiene los resultados de los sorteos que hay en bbdd de los dias anteriores
 */
class ResultadosBbdd
{

    private $hoy;
    private $resultados;
    private $date_format;

    public function __construct()
    {

        $this->date_format = 'Y-m-d H:i:s';
        $this->hoy = new DrupalDateTime(); // TODO cuando pase esto porner hoy, 

        //$format = 'd.m.y H:i:s'; // QUITAR
        //$date_str = '14.03.20 09:12:00'; // QUITAR Se queda congelado el dia 14 de marzo sabado 
        //$this->hoy = DateTimePlus::createFromFormat($format, $date_str); // QUITAR 
        $this->resultados = [];
    }

    public function getResultadosJuego($gameid, $cuantos)
    {

        if (!$gameid) {
            return null;
        } else {
            switch ($gameid) {
                case "LNAC":
                    return $this->dameResultadosHoyLnac(16, $cuantos);
                    break;
                case "EMIL":
                    return $this->dameResultadosHoyEmil(17, $cuantos);
                    break;
                case "LAPR":
                    return $this->dameResultadosHoyLapr(19, $cuantos);
                    break;
                case "BONO":
                    return $this->dameResultadosHoyBono(18, $cuantos);
                    break;
                case "ELGR":
                    return $this->dameResultadosHoyElgr(21, $cuantos);
                    break;
                case "LAQU":
                    return $this->dameResultadosHoyLaqu(20, $cuantos);
                    break;
                case "QGOL":
                    return $this->dameResultadosHoyQgol(22, $cuantos);
                    break;
                case "LOTU":
                    return $this->dameResultadosHoyLotu(23, $cuantos);
                    break;
                case "QUPL":
                    return $this->dameResultadosHoyQupl(24, $cuantos);
                    break;
            }
        }
    }

    private function dameResultadosHoyLnac($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $res['dia_semana'] = $val->dia_semana;
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['nombre'] = $val->nombre;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['primerpremio'] = sprintf("%05s", $val->combinacion->primer_premio);
                $res['segundopremio'] = sprintf("%05s", $val->combinacion->segundo_premio);
            }

            $res['decimo'] = $node->field_decimo_sorteo->entity->getFileUri();
            $res['decimo_url'] = file_create_url($node->field_decimo_sorteo->entity->getFileUri());

            array_push($resultado, $res);
        }
        return $resultado;
    }
    private function dameResultadosHoyEmil($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyLapr($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['joker'] = $val->joker->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyBono($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyElgr($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyLaqu($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['partidos'] = $val->partidos;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyQgol($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['partidos'] = $val->partidos;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }
    private function dameResultadosHoyLotu($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function dameResultadosHoyQupl($termid, $cuantos)
    {
        $resultado = [];
        $nodes = $this->querySorteos($termid, $cuantos);
        foreach ($nodes as $key => $node) {
            $selae = json_decode($node->field_selae->getString());
            foreach ($selae as $val) {
                $fecha = DateTimePlus::createFromFormat($this->date_format, $val->fecha_sorteo);
                $res['dia'] = (int) $fecha->format('d');
                $res['fecha_sorteo'] = $val->fecha_sorteo;
                $res['dia_semana'] = $val->dia_semana;
                $res['combinacion'] = $val->combinacion;
                $res['escrutinio'] = $val->escrutinio;
                $res['apuestas'] = $val->apuestas;
                $res['recaudacion'] = $val->recaudacion;
                $res['premio_bote'] = $val->premio_bote;
                $res['premios'] = $val->premios;
            }

            array_push($resultado, $res);
        }
        return $resultado;
    }

    private function querySorteos($termid, $cuantos)
    {
        $nids = \Drupal::entityQuery('node')
            ->condition('type', 'sorteo')
            ->condition('status', 1)
            ->condition('field_fecha_sorteo', $this->hoy, '<=')
            ->condition('field_sorteo', $termid)
            ->sort('field_fecha_sorteo', 'DESC')
            ->range(0, $cuantos)
            ->execute();
        /** @var \Drupal\node\NodeInterface $node */
        return  \Drupal\node\Entity\Node::loadMultiple($nids);
    }
}
