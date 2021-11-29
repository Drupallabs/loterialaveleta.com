<?php

namespace Drupal\sorteos\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\display\DisplayPluginBase;

/**
 * Filters by given list of sorteo title options.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("views_sorteos_caducados")
 */
class SorteosCaducados extends FilterPluginBase
{
    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->valueTitle = t('Sorteos Caducados');
    }

    /**
     * Override the query so that no filtering takes place if the user doesn't
     * select any options.
     */
    public function query()
    {
        /*$today = new \DateTime('now', new \DateTimezone('UTC'));
        dump($today);
        die;*/
        if (!empty($this->value)) {
            // parent::query();
        }
    }
}
