<?php

namespace Drupal\sorteos;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class ImporterListBuilder extends ConfigEntityListBuilder
{

    /**
     * {@inheritdoc}
     */
    public function buildHeader()
    {
        $header['label'] = $this->t('Importer');
        $header['id'] = $this->t('Machine name');
        $header['active'] = 'Activo';
        $header['tipo'] = 'Tipo de sorteo';
        $header['fecha'] = 'Parametro Fecha';
        $header['dias'] = 'Dias';
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        $row['label'] = $entity->label();
        $row['id'] = $entity->id();
        $row['active'] = ($entity->getActive()) ? 'Si' : 'No';
        $row['tipo'] = $entity->getBundle();
        $row['fecha'] = ($entity->getParamFecha()) ? 'Si' : 'No';
        $row['dias'] = $entity->getDias();
        return $row + parent::buildRow($entity);
    }
}
