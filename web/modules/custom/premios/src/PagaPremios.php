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
        dump('pagando');
    }
}
