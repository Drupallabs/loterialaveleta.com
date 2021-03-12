<?php

namespace Drupal\premios;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\CronInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\premios\Mail\MailPremios;
use Drupal\sorteos\SorteosBbdd;

/**
 * Default cron implementation.
 */
class Cron implements CronInterface
{

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * The time.
     *
     * @var \Drupal\Component\Datetime\TimeInterface
     */
    protected $time;

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
     * Constructs a new Cron object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     * @param \Drupal\Component\Datetime\TimeInterface $time
     *   The time.
     * @param \Drupal\premios\PremiosManagerInterface $premios_manager
     *   The premios manager.  
     * @param \Drupal\premios\Mail\MailPremios $mailPremios
     *   The premios mail manager.
     * @param \Drupal\sorteos\SorteosBbdd $sorteos_bbdd
     *   The premios mail manager.
     */
    public function __construct(
        EntityTypeManagerInterface $entity_type_manager,
        TimeInterface $time,
        PremiosManager $premios_manager,
        MailPremios $mailPremios,
        SorteosBbdd $sorteos_bbdd
    ) {
        $this->entityTypeManager = $entity_type_manager;
        $this->time = $time;
        $this->premios_manager = $premios_manager;
        $this->mailPremios = $mailPremios;
        $this->sorteosBbdd = $sorteos_bbdd;
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        //$this->checkPremiosPedidos();
        $this->checkSorteosYesterday();
    }

    protected function checkSorteosYesterday()
    {
        //sorteo que se celebro ayer de loteria nacional
        $lnac = $this->sorteosBbdd->dameUltimoSorteoLnac();
        $ultimo_sorteo_lnac_id = $lnac->id;
        // buscamos todos los productos que tengan el sorteo de loteria nacional
        
        dump($lnac);
        die;
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
