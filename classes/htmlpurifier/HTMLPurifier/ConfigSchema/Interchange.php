<?php


class HTMLPurifier_ConfigSchema_Interchange
{

    /**
     * Name of the application this schema is describing.
     * @type string
     */
    public $name;

    /**
     * Array of Directive ID => array(directive info)
     * @type HTMLPurifier_ConfigSchema_Interchange_Directive[]
     */
    public $directives = array();

    /**
     * Adds a directive array to $directives
     * @param HTMLPurifier_ConfigSchema_Interchange_Directive $directive
     * @throws HTMLPurifier_ConfigSchema_Exception
     */
    public function addDirective($directive)
    {
        if (isset($this->directives[$i = $directive->id->toString()])) {
            throw new HTMLPurifier_ConfigSchema_Exception("Cannot redefine directive '$i'");
        }
        $this->directives[$i] = $directive;
    }

    /**
     * Convenience function to perform standard validation. Throws exception
     * on failed validation.
     */
    public function validate()
    {
        $validator = new HTMLPurifier_ConfigSchema_Validator();
        return $validator->validate($this);
    }
}

// vim: et sw=4 sts=4
