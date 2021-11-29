<?php

namespace Drupal\premios\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\State\State;

/**
 * @package Drupal\premios\Commands
 */
class PremiosCommands extends DrushCommands
{
    /**
     * The state
     * @var \Drupal\Core\State\State
     */
    protected $state;


    /**
     * @param \Drupal\Core\State\State
     */
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    /**
     * Commando que busca en los Pedidos de los usuarios, y si tiene algun premio, se lo paga al monedero
     * 
     * @command paga-premios-pedido-usuario
     * @aliases paga
     */

    public function PagaPedidosUsuario()
    {
        $this->state->set('premios.last_paga', \Drupal::time()->getRequestTime());
        $premiospaga = \Drupal::service('premios.paga');
        $premiospaga->pagando();
    }
}
