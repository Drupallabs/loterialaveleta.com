<?php

namespace Drupal\premios;

use Drupal\Core\CronInterface;

/**
 * Default cron implementation.
 */
class Cron implements CronInterface
{


    public function run()
    {
        // $this->checkSorteosYesterday();
    }

    protected function checkSorteosYesterday()
    {

        //sorteo que se celebro ayer de loteria nacional
        $lnac = $this->sorteosBbdd->dameUltimoSorteoLnac();
        //$ultimo_sorteo_lnac_id = $lnac->id;
        $ultimo_sorteo_lnac_id = 693; // san valentin
        $sorteo_id = 1118709012; // san valentin

        $batch = [
            'title' => 'Comprobando Decimos..',
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
                $batch['operations'][] = [[$this, 'comprobarDecimoSorteo'], [$numero, $sorteo_id]];
            }
        }

        batch_set($batch);
    }

    private function comprobarDecimoSorteo($numero, $sorteo_id)
    {

        $this->comprobarLnac->comprobarDecimoSorteo(trim($numero), trim($sorteo_id));
    }

    /* 
    * Busca los pedidos que todavia no han pagado el premio,
    * y se lo paga en el Monedero, si tiene premio
    */
    protected function checkPremiosPedidos()
    {

        //$ahora = $this->time->getCurrentTime();
        // One week back
        $weekback = date('jS F Y', time() + (60 * 60 * 24 * -7));
        $t_weekback = strtotime($weekback);
        $order_storage = $this->entityTypeManager->getStorage('commerce_order');
        $query = $order_storage->getQuery()
            ->condition('type', 'default')
            ->condition('state', 'completed')
            ->notExists('field_pago_premio_pedido')
            // No tiene asignado ninguna forma de pago de premio, por tanto no esta pagado el premio
            ->condition('completed', $t_weekback, '>=')
            // Pedidos de la ultima semana
            ->accessCheck(FALSE);
        $order_ids = $query->execute();
        //$rawSqlQuery = (string) dpq($query);
        //echo $rawSqlQuery;
        if (!$order_ids) {
            return;
        }
        /** @var \Drupal\commerce_order\Entity\OrderInterface[] $orders */
        $orders = $order_storage->loadMultiple($order_ids);
        foreach ($orders as $order) {
            $premios = $this->premios_manager->checkPremiosOrder($order);
            //$this->mailPremios->send();
        }
    }
}
