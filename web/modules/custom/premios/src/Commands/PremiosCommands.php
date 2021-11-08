<?php

namespace Drupal\premios\Commands;

use Drush\Commands\DrushCommands;

/**
 * @package Drupal\premios\Commands
 */
class PremiosCommands extends DrushCommands
{
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
