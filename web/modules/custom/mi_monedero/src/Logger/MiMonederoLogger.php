<?php

namespace Drupal\mi_monedero\Logger;

use Psr\Log\LoggerInterface;
use Drupal\Core\Logger\RfcLoggerTrait;

/*
 * A logger for Mi Monedero
*/

class MiMonederoLogger implements LoggerInterface
{
    use RfcLoggerTrait;

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        // Do stuff
    }    
}
