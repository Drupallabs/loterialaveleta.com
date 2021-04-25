<?php

namespace Drupal\premios;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Premios manager class.
 */
class PremiosManager
{
    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;
    /**
     * Constructs a new Cron object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     */

    public function __construct(EntityTypeManagerInterface $entity_type_manager)
    {
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * Busca todas las lineas de pedidos que tienen este producto y les paga el premio en el monedero del usuario propiertario del pedido
     */
    public function payPremiosProduct(ProductInterface $product, $premio)
    {
        $product_variation = reset($product->getVariations());
        if ($product_variation) {
            $order_storage = $this->entityTypeManager->getStorage('commerce_order_item');
            $query = $order_storage->getQuery()
                ->condition('purchased_entity', $product_variation->id());

            $ids = $query->execute();
            $commerce_order_items = $order_storage->loadMultiple($ids);

            foreach ($commerce_order_items as $commerce_order_item) {
                $commerce_order = $commerce_order_item->getOrder();
                
            }
        }
    }

    /**
     * Check los premios que tiene un pedido, de las lineas de pedidos 
     *
     * @param \Drupal\commerce_order\Entity\OrderInterface $order
     *   The recurring order.
     */
    public function checkPremiosOrder(OrderInterface $order)
    {
        /*
        $comprobacion = (object)[]; // guarda el resultado de la comprobacion
        $order_items = $order->getItems();
        //dump($order_items);
        // OrderRefresh skips empty orders, an order without items can't stay open.
        if (!$order_items) {
            $order->set('state', 'canceled');
        }

        foreach ($order_items as $order_item) {
            $ordervar = $order_item->getPurchasedEntity();
            $product = $ordervar->getProduct();
            $decimo = trim($product->get('field_numero_decimo')->value);

            $sorteo = $product->get('field_sorteo_3')[0]->getValue();
            $sorteo_id = $sorteo['target_id'];
            $reso = new ResultadosConnection();
            $sorteo = Sorteo::load($sorteo_id);
            // Si el sorteo no se ha celebrado todavia no hacemos nada
            //$hoy = DateTimePlus::createFromFormat('Y-m-dTH:i:s', date('Y-m-dTH:i:s'));
            //dump($hoy);
            //die;
            //$sorteo_fecha = DateTimePlus::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $sorteo->getFecha());
            //$diff = $hoy->diff($sorteo_fecha);

            $cdc = $sorteo->getIdSorteo();
            if (!$sorteo) {
                return;
            } else {
                $resultado = $reso->getPremioDecimoWeb($cdc);
                $compo = new ComprobarDecimo($decimo, $sorteo, $resultado);
                $comprobacion = $compo->dameResultadosComprobacionLight();
                return $comprobacion;
                //dump($comprobacion);
            }
        }*/
    }
}
