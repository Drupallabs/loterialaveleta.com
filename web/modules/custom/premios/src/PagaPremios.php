<?php

namespace Drupal\premios;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\sorteos\SorteosBbdd;
use Drupal\resultados\Services\ComprobarDecimoLnac;
use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Database\Connection;
use Drupal\commerce_product\Entity\Product;

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
     * Clase Connection Database
     * 
     * @var \Drupal\Core\Database\Connection
     */
    protected $database;

    /**
     * Constructs a new Cron object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     * @param \Drupal\premios\PremiosManagerInterface $premios_manager
     *   The premios manager.  
     * @param \Drupal\sorteos\SorteosBbdd $sorteos_bbdd
     * @param \Drupal\resultados\Servicios\ComprobarDecimoLnac $comprobar_lnac
     * @param \Drupal\Core\Database\Connection $database
     *   
     */
    public function __construct(
        EntityTypeManagerInterface $entity_type_manager,
        PremiosManager $premios_manager,
        SorteosBbdd $sorteos_bbdd,
        ComprobarDecimoLnac $comprobar_lnac,
        Connection $database
    ) {
        $this->entityTypeManager = $entity_type_manager;
        $this->premios_manager = $premios_manager;
        $this->sorteosBbdd = $sorteos_bbdd;
        $this->comprobarLnac = $comprobar_lnac;
        $this->database = $database;
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
        /*$product_storage = $this->entityTypeManager->getStorage('commerce_product');
        $query = $product_storage->getQuery();
        $query->condition('field_sorteo_3', (int)$ultimo_sorteo_lnac_id);
        $ids = $query->execute();*/

        $query = $this->database->select('commerce_product', 'cp');
        $query->fields(
            'cp',
            ['product_id']
        );
        $query->fields(
            'cpn',
            ['field_numero_decimo_value']
        );
        $query->join('commerce_product__field_sorteo_3', 'cps', 'cps.entity_id = cp.product_id');
        $query->join('commerce_product__field_numero_decimo', 'cpn', 'cpn.entity_id = cp.product_id');
        $query->condition('cps.field_sorteo_3_target_id', $ultimo_sorteo_lnac_id);
        $products = $query->execute();

        //print_r($query->__toString());

        if (!empty($products)) {
            //$products = $order_storage->loadMultiple($ids);
            // Si hay Productos creados de ese sorteo
            $operations = [];
            if ($products) {
                foreach ($products as $product) {
                    $product_entity = Product::load($product->product_id);
                    $numero = $product->field_numero_decimo_value;
                    $operations[] = [
                        [
                            $this->comprobarDecimoSorteo($numero, $sorteo_id, $product_entity),
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
