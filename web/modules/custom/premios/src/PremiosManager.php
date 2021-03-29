<?php

namespace Drupal\premios;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\resultados\ComprobarDecimo;
use Drupal\sorteos\entity\Sorteo;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\resultados\ResultadosConnection;

/**
 * Premios manager class.
 */
class PremiosManager
{
    /**
     * Check los premios que tiene un pedido, de las lineas de pedidos 
     *
     * @param \Drupal\commerce_order\Entity\OrderInterface $order
     *   The recurring order.
     */
    public function checkPremiosOrder(OrderInterface $order)
    {
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
        }
    }
}
