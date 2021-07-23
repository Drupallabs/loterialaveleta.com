<?php

namespace Drupal\premios;

/**
 * Provides the interface for the Premios module's cron.
 */
interface CronInterface
{

    /**
     * Runs the cron.
     */
    public function run();
}
