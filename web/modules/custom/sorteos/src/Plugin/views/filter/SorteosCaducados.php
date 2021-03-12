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
     * Override the query
     */
    public function query()
    {
        // Una hora antes 
        $today = new \DateTime('now', new \DateTimezone('UTC'));
        $todayf = $today->format('Y-m-d H:i:s');
        $this->query->addWhere('AND', 'sorteo_commerce_product__field_sorteo_3.fecha', $todayf, '>=');
    }
}
