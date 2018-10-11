<?php

class HTMLPurifier_HTMLModule_XMLCommonAttributes extends HTMLPurifier_HTMLModule
{
    /**
     * @type st
     */
    public $name = 'XMLCommonAttributes';

    /**
     * @type array
     */
    public $attr_collections = array(
        'Lang' => array(
            'xml:lang' => 'LanguageCode',
        )
    );
}

// vim: et sw=4 sts=4
