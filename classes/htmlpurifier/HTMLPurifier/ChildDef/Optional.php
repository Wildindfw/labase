<?php


class HTMLPurifier_ChildDef_Optional extends HTMLPurifier_ChildDef_Required
{
    /**
     * @type bool
     */
    public $allow_empty = true;

    /**
     * @type string
     */
    public $type = 'optional';

    /**
     * @param array $children
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function validateChildren($children, $config, $context)
    {
        $result = parent::validateChildren($children, $config, $context);
        // we assume that $children is not modified
        if ($result === false) {
            if (empty($children)) {
                return true;
            } elseif ($this->whitespace) {
                return $children;
            } else {
                return array();
            }
        }
        return $result;
    }
}

// vim: et sw=4 sts=4
