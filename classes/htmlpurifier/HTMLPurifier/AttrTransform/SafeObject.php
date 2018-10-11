<?php

/**
 * Writes default type for all objects. Currently only supports flash.
 */
class HTMLPurifier_AttrTransform_SafeObject extends HTMLPurifier_AttrTransform
{
    public $name = "SafeObject";

    /**
     * @param array $attr
     fier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['type'])) {
            $attr['type'] = 'application/x-shockwave-flash';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
