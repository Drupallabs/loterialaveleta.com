<?php

namespace Drupal\sorteos\Plugin\Importer;

//use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\File\FileSystemInterface;
use Drupal\sorteos\Entity\SorteoInterface;
use Drupal\sorteos\Plugin\ImporterBase;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;


/**
 * Sorteo importer from a JSON format.
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("JSON Importer")
 * )
 */
class JsonImporter extends ImporterBase
{

    /**
     * {@inheritdoc}
     */
    public function getConfigurationForm(\Drupal\sorteos\Entity\ImporterInterface $importer)
    {
        $form = [];
        $config = $importer->getPluginConfiguration();
        $form['url'] = [
            '#type' => 'url',
            '#default_value' => isset($config['url']) ? $config['url'] : '',
            '#title' => $this->t('Url'),
            '#description' => $this->t('The URL to the import resource'),
            '#required' => TRUE,
        ];
        return $form;
    }


    /**
     * {@inheritdoc}
     */
    public function import()
    {
        $sorteos = $this->getData();
        if (!$sorteos) {
            return FALSE;
        }
        // No hay sorteos devuelve un string
        if (!is_array($sorteos)) {
            return TRUE;
        }

        /*
        $batch_builder = (new BatchBuilder())
            ->setTitle($this->t('Importing sorteos'))
            ->setFinishCallback([$this, 'importSorteosFinished']);

        //$batch_builder->addOperation([$this, 'clearMissing'], [$sorteos]);
        $batch_builder->addOperation([$this, 'importSorteos'], [$sorteos]);
        batch_set($batch_builder->toArray());

        if (PHP_SAPI == 'cli') {
            drush_backend_batch_process();
        }*/


        foreach ($sorteos as $sorteo) {
            $this->persistSorteo2($sorteo);
        }

        return TRUE;
    }

    /**
     * Batch operation to remove the sorteos which are no longer in the list of
     * sorteos coming from the JSON file.
     *
     * @param $sorteos
     * @param $context
     */

    public function clearMissing($sorteos, &$context)
    {
        if (!isset($context['results']['cleared'])) {
            $context['results']['cleared'] = [];
        }

        if (!$sorteos) {
            return;
        }

        $ids = [];
        foreach ($sorteos as $sorteo) {
            $ids[] = $sorteo->id;
        }

        $ids = $this->entityTypeManager->getStorage('sorteo')->getQuery()
            ->condition('remote_id', $ids, 'NOT IN')
            ->execute();
        if (!$ids) {
            $context['results']['cleared'] = [];
            return;
        }

        $entities = $this->entityTypeManager->getStorage('sorteo')->loadMultiple($ids);

        /** @var \Drupal\sorteos\Entity\SorteoInterface $entity */
        foreach ($entities as $entity) {
            $context['results']['cleared'][] = $entity->getName();
        }
        $context['message'] = $this->t('Removing @count sorteos', ['@count' => count($entities)]);
        $this->entityTypeManager->getStorage('sorteo')->delete($entities);
    }

    /**
     * Batch operation to import the sorteos from the JSON file.
     *
     * @param $sorteos
     * @param $context
     */
    public function importSorteos($sorteos, &$context)
    {
        if (!isset($context['results']['imported'])) {
            $context['results']['imported'] = [];
        }

        if (!$sorteos) {
            return;
        }

        $sandbox = &$context['sandbox'];
        if (!$sandbox) {
            $sandbox['progress'] = 0;
            $sandbox['max'] = count($sorteos);
            $sandbox['sorteos'] = $sorteos;
        }

        $slice = array_splice($sandbox['sorteos'], 0, 3);

        foreach ($slice as $sorteo) {
            $context['message'] = $this->t('Importando sorteo @id_sorteo', ['@id_sorteo' => $sorteo->id_sorteo]);
            $this->persistSorteo2($sorteo);
            $context['results']['imported'][] = $sorteo->id_sorteo;
            $sandbox['progress']++;
        }

        $context['finished'] = $sandbox['progress'] / $sandbox['max'];
        //dump($context['finished']);
    }

    /**
     * Callback for when the batch processing completes.
     *
     * @param $success
     * @param $results
     * @param $operations
     */
    public function importSorteosFinished($success, $results, $operations)
    {
        if (!$success) {
            //drupal_set_message($this->t('There was a problem with the batch'), 'error');
            \Drupal::messenger()->addError('Hubo un problema en la ejecucion del batch');
            return;
        }
        $imported = count($results['imported']);
        if ($imported == 0) {
            \Drupal::messenger()->addStatus('No hay sorteos para ser importados.');
        } else {
            \Drupal::messenger()->addStatus($this->formatPlural($imported, '1 sorteo importando.', '@count sorteos importados.'));
        }
    }

    /**
     * Loads the sorteo data from the remote URL.
     *
     * @return \stdClass
     */
    private function getData()
    {

        /** @var \Drupal\sorteos\Entity\ImporterInterface $importer_config */
        $importer_config = $this->configuration['config'];

        $config = $importer_config->getPluginConfiguration();
        $url = isset($config['url']) ? $config['url'] : NULL;
        if (!$url) {
            return NULL;
        }
        if ($importer_config->getParamFecha()) {
            $hoy = DateTimePlus::createFromFormat('Ymd', date('Ymd'));
            // Si pone dias a 0 es hoy
            if ($importer_config->getDias() == '0') {
                $url .= '&fecha_sorteo=' . $hoy->format('Ymd');
            } else {
                $dias = 7;
                if ($importer_config->getDias()) {
                    $dias = $importer_config->getDias();
                }
                $url .= '&fecha_sorteo=' . $hoy->modify('+' . $dias . ' days')->format('Ymd');
            }
        }
        //dump($url);
        $request = $this->httpClient->get($url);
        $string = $request->getBody()->getContents();
        return json_decode($string);
    }

    /**
     * Saves a Sorteo entity from the remote data.
     * 
     * @param \stdClass $data 
     */
    private function persistSorteo2($data)
    {
        /** @var \Drupal\sorteos\Entity\ImporterInterface $config */
        $config = $this->configuration['config'];

        $existing = $this->entityTypeManager->getStorage('sorteo')->loadByProperties(['id_sorteo' => $data->id_sorteo]);

        if (!$existing) {
            $values = [
                'id_sorteo' => $data->id_sorteo,
                'type' => $config->getBundle(),
            ];
            /** @var \Drupal\sorteos\Entity\SorteoInterface $sorteo */
            $sorteo = $this->entityTypeManager->getStorage('sorteo')->create($values);
        } else {
            // actualizamos campos nuevos 
            $sorteo = reset($existing);
        }
        try {
            $dtime = DateTimePlus::createFromFormat('Y-m-d H:i:s', $data->fecha_sorteo);
            $dtimeFormat = $dtime->format('d/m/Y');
            $dtime->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
            $subnombre = ' ' . ucfirst($data->dia_semana) . ' ' . $dtimeFormat;
            if (!$data->nombre) $sorteo->setName(ucfirst($config->getBundle()) . $subnombre);
            else $sorteo->setName($data->nombre);
            $sorteo->setDiaSemana(ucfirst($data->dia_semana));
            $dtimeFormat2 = $dtime->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
            $sorteo->setFecha($dtimeFormat2);
            $sorteo->setPremioBote($data->premio_bote);
            $sorteo->setApuestas($data->apuestas);
            $sorteo->setRecaudacion($data->recaudacion);
            $sorteo->setPremios($data->premios);
            $sorteo->setFondoBote($data->fondo_bote);
            $sorteo->setEscrutinio($data->escrutinio);
            // ahora los capos especificos de cada sorteo
            switch ($config->getBundle()) {
                case 'loteria_nacional':
                    $sorteo->set('field_primer_premio', $data->combinacion->primer_premio);
                    $sorteo->set('field_segundo_premio', $data->combinacion->segundo_premio);
                    $sorteo->set('field_tercer_premio', $data->combinacion->tercer_premio);
                    $sorteo->set('field_num_sorteo', $data->num_sorteo);
                    $sorteo->set('field_ano_sorteo', $data->anyo);
                    $sorteo->set('field_precio_decimo', $data->tenthPrice);
                    //$this->handleSorteoImage($data, $sorteo);
                    break;
                case 'euromillones':
                    $this->handleEuromillones($data, $sorteo);
                    break;
                case 'primitiva':
                    $this->handlePrimitiva($data, $sorteo);
                    break;
                case 'gordo_primitiva':
                    $this->handleGordo($data, $sorteo);
                    break;
                case 'bonoloto':
                    $this->handleBonoloto($data, $sorteo);
                    break;
                case 'quiniela':
                    $this->handleQuiniela($data, $sorteo);
                    break;
                case 'quinigol':
                    $this->handleQuinigol($data, $sorteo);
                    break;
                case 'lototurf':
                    $this->handleLototurf($data, $sorteo);
                    break;
                case 'quintuple_plus':
                    $this->handleQuintuplePlus($data, $sorteo);
                    break;
            }
        } catch (\Exception $e) {
            //\Drupal::logger('sorteos')->error($e->getMessage()var_dump($data->escrutinio));
            \Drupal::logger('sorteos')->error($e->getMessage());
        }

        $sorteo->save();
        return;

        /*
        if (!$config->updateExisting()) {
            return;
        }*/
    }

    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleEuromillones($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'estrella1' => $com[5],
                    'estrella2' => $com[6]
                );
                $sorteo->set('field_combinacion_euromillones', $combi);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handlePrimitiva($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                preg_match_all('#\((.*?)\)#', $data->combinacion, $match);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'bola6' => $com[5],
                    'complementario' =>  trim($match[1][0]),
                    'reintegro' =>  trim($match[1][1])
                );

                $sorteo->set('field_combinacion_primitiva', $combi);
                $sorteo->set('field_joker', $data->joker->combinacion);
                $sorteo->setEscrutinioJoker($data->escrutinio_joker);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }

    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleGordo($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                preg_match_all('#\((.*?)\)#', $data->combinacion, $match);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'clave' =>  trim($match[1][0])
                );
                $sorteo->set('field_combinacion_gordo', $combi);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleBonoloto($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                preg_match_all('#\((.*?)\)#', $data->combinacion, $match);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'bola6' => $com[5],
                    'complementario' =>  trim($match[1][0]),
                    'reintegro' =>  trim($match[1][1])
                );
                $sorteo->set('field_combinacion_bonoloto', $combi);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleQuiniela($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->partidos) {
                $sorteo->set('field_partidos', json_encode($data->partidos, JSON_UNESCAPED_UNICODE));
                $sorteo->set('field_temporada', $data->temporada);
                $sorteo->set('field_jornada', $data->jornada);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleQuinigol($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->partidos) {
                $sorteo->set('field_partidos', json_encode($data->partidos, JSON_UNESCAPED_UNICODE));
                $sorteo->set('field_temporada', $data->temporada);
                $sorteo->set('field_jornada', $data->jornada);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleLototurf($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                preg_match_all('#\((.*?)\)#', $data->combinacion, $match);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'bola6' => $com[5],
                    'caballo' =>  trim($match[1][0]),
                    'reintegro' =>  trim($match[1][1])
                );
                $sorteo->set('field_combinacion_lototurf', $combi);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }

    /**
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleQuintuplePlus($data, SorteoInterface $sorteo)
    {
        try {
            if ($data->combinacion) {
                $com = explode("-", $data->combinacion);
                preg_match_all('#\((.*?)\)#', $data->combinacion, $match);
                $combi = array(
                    'bola1' => $com[0],
                    'bola2' => $com[1],
                    'bola3' => $com[2],
                    'bola4' => $com[3],
                    'bola5' => $com[4],
                    'bola6' => trim($match[1][0]),
                );
                $sorteo->set('field_combinacion_quintuple', $combi);
            }
        } catch (\Exception $e) {
            \Drupal::logger('sorteos')->error($e->getMessage());
        }
    }
    /**
     * Imports the image of the sorteo and adds it to the Sorteo entity.
     *
     * @param $data
     * @param \Drupal\sorteos\Entity\SorteoInterface $sorteo
     */
    private function handleSorteoImage($data, SorteoInterface $sorteo)
    {

        $name = 'https://www.loteriasyapuestas.es/f/loterias/imagenes/estaticos/capillas/s' . $data->num_sorteo . '_' . $data->anyo . '_pc.png';
        $image = file_get_contents($name);
        $filename = basename($name);
        if (!$image) {
            \Drupal::messenger()->addError('Problema importando la imagen.' . $name);
        }

        /** @var \Drupal\file\FileInterface $file */
        $file = file_save_data($image, 'public://decimos-lnac/' . $filename, FileSystemInterface::EXISTS_REPLACE);
        if (!$file) {
            \Drupal::messenger()->addError('Problema guardando la imagen. ' . $name);
            return;
        }

        $sorteo->set('field_decimo_imagen', $file->id());
    }
}
