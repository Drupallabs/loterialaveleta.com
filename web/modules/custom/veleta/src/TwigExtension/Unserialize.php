<?php

namespace Drupal\veleta\TwigExtension;
class Unserialize extends \Twig_Extension
{
    /**
     * Generates a list of all Twig filters that this extension defines.
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('unserialize', array($this, 'unserialize')),
        ];
    }
    /**
     * Gets a unique identifier for this Twig extension.
     */
    public function getName()
    {
        return 'veleta.twig_extension.unserialize';
    }

    public static function unserialize($string)
    {
        return unserialize($string);
    }
}
