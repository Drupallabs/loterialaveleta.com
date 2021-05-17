<?php

namespace Drupal\premios;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\sorteos\SorteosBbdd;
use Drupal\resultados\Services\ComprobarDecimoLnac;
use Drupal\commerce_product\Entity\ProductInterface;

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
     * @param \Drupal\sorteos\SorteosBbdd $sorteos_bbdd
     * @param \Drupal\resultados\Servicios\ComprobarDecimoLnac $comprobar_lnac
     *   
     */
    public function __construct(
        EntityTypeManagerInterface $entity_type_manager,
        PremiosManager $premios_manager,
        SorteosBbdd $sorteos_bbdd,
        ComprobarDecimoLnac $comprobar_lnac
    ) {
        $this->entityTypeManager = $entity_type_manager;
        $this->premios_manager = $premios_manager;
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
        $ultimo_sorteo_lnac_id = $lnac->id;
        $sorteo_id = $lnac->id_sorteo;

        // buscamos todos los productos que tengan el sorteo de loteria nacional
        $order_storage = $this->entityTypeManager->getStorage('commerce_product');
        $query = $order_storage->getQuery();
        $query->condition('field_sorteo_3', $ultimo_sorteo_lnac_id);
        $ids = $query->execute();

        if (!empty($ids)) {
            $products = $order_storage->loadMultiple($ids);

            // Si hay Productos creados de ese sorteo
            $operations = [];
            if ($products) {
                foreach ($products as $product) {
                    $numeroar = $product->field_numero_decimo->getValue();
                    $numero = $numeroar[0]["value"];
                    $operations[] = [
                        [
                            $this->comprobarDecimoSorteo($numero, $sorteo_id, $product),
                        ],
                    ];
                }
            }

            $batch = [
                'title' => 'Comprobando Decimos..',
                'progress_message' => t('Processed @current out of @total.'),
                //'error_message'    => t('Error comprobando decimos'),
                'operations' => $operations,
                'finished' => $this->finishedPaid(),
            ];
            batch_set($batch);
        }
    }

    function comprobarDecimoSorteo($numero, $sorteo_id, ProductInterface $product)
    {
        $premio = $this->comprobarLnac->comprobarDecimoSorteo(trim($numero), trim($sorteo_id));

        if ($premio) {
            $this->premios_manager->payPremiosProduct($product, $premio);
        }
    }

    function finishedPaid()
    {
        dump('finishedPaid');
    }
}
