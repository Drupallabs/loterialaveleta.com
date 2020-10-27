<?php

namespace Drupal\sorteos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents a Sorteo entity.
 */
interface SorteoInterface extends ContentEntityInterface, EntityChangedInterface
{

    /**
     * Gets the Sorteo name.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the Sorteo name.
     *
     * @param string $name
     *
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setName($name);

    /**
     * Gets the Sorteo number.
     *
     * @return string
     */
    public function getIdSorteo();

    /**
     * Sets the Sorteo number.
     *
     * @param string $id_sorteo
     * 
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setIdSorteo($id_sorteo);


    /**
     * Gets the Sorteo dia semana.
     *
     * @return string
     */
    public function getDiaSemana();

    /**
     * Sets the Sorteo Dia Semana.
     *
     * @param string $dia_semana
     * 
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setDiaSemana($dia_semana);


    /**
     * Gets the Sorteo premio bote.
     *
     * @return string
     */
    public function getPremioBote();

    /**
     * Sets the Sorteo premio bote.
     *
     * @param string $premio_bote
     * 
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setPremioBote($premio_bote);


    public function getApuestas();
    public function setApuestas($apuestas);

    public function getRecaudacion();
    public function setRecaudacion($recaudacion);

    public function getPremios();
    public function setPremios($premios);

    public function getFondoBote();
    public function setFondoBote($fondo_bote);


    public function getEscrutinio();
    public function setEscrutinio($escrutinio);


    public function getEscrutinioJoker();
    public function setEscrutinioJoker($escrutinio_joker);
    
    public function getResultados();
    public function setResultados($resultados);

    /**
     * Gets the Fecha source.
     *
     * @return string
     */
    public function getFecha();

    /**
     * Sets the Fecha source.
     *
     * @param string $fecha
     *
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setFecha($fecha);

    /**
     * Gets the Sorteo creation timestamp.
     *
     * @return int
     */
    public function getCreatedTime();

    /**
     * Sets the Sorteo creation timestamp.
     *
     * @param int $timestamp
     *
     * @return \Drupal\sorteos\Entity\SorteoInterface
     *   The called Sorteo entity.
     */
    public function setCreatedTime($timestamp);
}
