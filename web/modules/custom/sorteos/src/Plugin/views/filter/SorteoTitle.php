<?php
namespace Drupal\sorteos\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Filters by given list of sorteo title options.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("views_sorteo_titles")
 */
class SorteoTitles extends InOperator
{

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->valueTitle = t('Allowed node titles');
        $this->definition['options callback'] = array($this, 'generateOptions');
    }

    /**
     * Override the query so that no filtering takes place if the user doesn't
     * select any options.
     */
    public function query()
    {
        if (!empty($this->value)) {
            parent::query();
        }
    }

    /**
     * Skip validation if no options have been chosen so we can use it as a
     * non-filter.
     */
    public function validate()
    {
        if (!empty($this->value)) {
            parent::validate();
        }
    }

    /**
     * Helper function that generates the options.
     * @return array
     */
    public function generateOptions()
    {
        // Array keys are used to compare with the table field values.
        return array(
            662 => 'Jueves 4',
            669 => 'Sabado 6',
            688 => 'Jueves 11'
        );
    }
}