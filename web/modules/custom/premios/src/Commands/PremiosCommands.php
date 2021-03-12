<?php

namespace Drupal\premios\Commands;

use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\premios\Commands
 */
class PremiosCommands extends DrushCommands
{
    /**
     * Commando que busca en los Pedidos de los usuarios, y si tiene algun premio, se lo paga al monedero
     * 
     * @command premios-pedido-usuario
     * @aliases ppu
     */

    public function PedidosUsuario()
    {
        $this->output()->writeln('pEDIDOs usuario');
    }
}
