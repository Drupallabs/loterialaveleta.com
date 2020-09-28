<?php

namespace Drupal\veleta\TwigExtension;

class JsonDecode extends \Twig_Extension
{
    /**
     * Generates a list of all Twig filters that this extension defines.
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('jsonDecode', array($this, 'jsonDecode')),
        ];
    }
    /**
     * Gets a unique identifier for this Twig extension.
     */
    public function getName()
    {
        return 'veleta.twig_extension.jsonDecode';
    }

    public static function jsonDecode($string)
    {
        return json_decode($string);
    }
}
