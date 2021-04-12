<?php

namespace Drupal\premios;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\premios\Mail\MailPremios;
use Drupal\sorteos\SorteosBbdd;
use Drupal\resultados\Services\ComprobarDecimoLnac;

class PagaPremios
{
    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * The premios manager.
     *
     * @var \Drupal\premios\PremiosManagerInterface
     */
    protected $premiosManager;


    /**
     * The premios mail.
     *
     * @var \Drupal\premios\Mail\MailPremios
     */
    protected $mailPremios;

    /**
     * The premios mail handler.
     *
     * @var \Drupal\premios\Mail\MailHandler
     */
    protected $mailHandler;

    /**
     * Sorteos Bbdd
     *
     * @var \Drupal\sorteos\SorteosBbdd
     */
    protected $sorteos_bbdd;

    /**
     * Clase Comprobar Lnac
     *
     * @var \Drupal\resultados\Services\ComprobarDecimoLnac
     */
    protected $comprobar_lnac;

    /**
     * Constructs a new Cron object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     * @param \Drupal\premios\PremiosManagerInterface $premios_manager
     *   The premios manager.  
     * @param \Drupal\premios\Mail\MailPremios $mailPremios
     *   The premios mail manager.
     * @param \Drupal\sorteos\SorteosBbdd $sorteos_bbdd
     * @param \Drupal\resultados\Servicios\ComprobarDecimoLnac $comprobar_lnac
     *   
     */
    public function __construct(
        EntityTypeManagerInterface $entity_type_manager,
        PremiosManager $premios_manager,
        MailPremios $mailPremios,
        SorteosBbdd $sorteos_bbdd,
        ComprobarDecimoLnac $comprobar_lnac
    ) {
        $this->entityTypeManager = $entity_type_manager;
        $this->premios_manager = $premios_manager;
        $this->mailPremios = $mailPremios;
        $this->sorteosBbdd = $sorteos_bbdd;
        $this->comprobarLnac = $comprobar_lnac;
    }

    public function pagando()
    {
        $this->checkSorteosYesterdayLnac();
    }

    /*
     * Busca todos los sorteos 
     */
    protected function checkSorteosYesterdayLnac()
    {

        //sorteo que se celebro ayer de loteria nacional
        $lnac = $this->sorteosBbdd->dameUltimoSorteoLnac();
        //$ultimo_sorteo_lnac_id = $lnac->id;
        $ultimo_sorteo_lnac_id = 693; // san valentin
        $sorteo_id = 1118709012; // san valentin

        $batch = [
            'title' => 'Comprobando Decimos..',
            'init_message' => 'Comprobando',
            'progress_message' => t('Processed @current out of @total.'),
            'error_message'    => t('Error comprobando decimos'),
            'operations' => [
                // [[$this, 'clearMissing'], [$products]],
            ],
            'finished' => [$this, 'comprobacionDecimosFinished'],
        ];

        // buscamos todos los productos que tengan el sorteo de loteria nacional
        $order_storage = $this->entityTypeManager->getStorage('commerce_product');
        $query = $order_storage->getQuery();
        $query->condition('field_sorteo_3', $ultimo_sorteo_lnac_id);
        $ids = $query->execute();
        $sorteos = $order_storage->loadMultiple($ids);

        // Si hay Productos creados de ese sorteo
        if ($sorteos) {
            foreach ($sorteos as $sorteo) {
                $numeroar = $sorteo->field_numero_decimo->getValue();
                $numero = $numeroar[0]["value"];
                //dump($ultimo_sorteo_lnac_id);
                $batch['operations'][] = [[$this, 'comprobarDecimoSorteo'], [$numero]];
                
            }
        }
        //dump('444444');

        batch_set($batch);
    }

    private function comprobarDecimoSorteo($numero, $sorteo_id)
    {
        dump('comprobar decimo sorteo');
        die;
        //$this->comprobarLnac->comprobarDecimoSorteo(trim($numero), trim($sorteo_id));
    }
}
