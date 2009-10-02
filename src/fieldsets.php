<?php

/**
 * Fieldsets
 *
 * Fieldset classes that are used to compose a Phorm instance.
 * 
 * @author Greg Thornton
 * @package Fieldsets
 * @see Phorm,Fields
 **/
 
class Fieldset
{
    public $id;
    public $name;
    public $label;
    public $field_names = array();
    
    function __construct($name, $label, $field_names=array()) {
        $this->id = 'id_' . $name;
        $this->name = $name;
        $this->label = $label;
        $this->field_names = $field_names;
    }
    
    
}

?>