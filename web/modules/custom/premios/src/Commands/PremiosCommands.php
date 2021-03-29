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
        $premiospaga = \Drupal::service('premios.paga');
        $premiospaga->pagando();
        $this->output()->writeln('Pedidos Usuario');
    }
}
