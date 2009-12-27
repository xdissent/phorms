<?php

/**
 * Widgets
 *
 * Widgets represent HTML field input tags.
 * 
 * @author Jeff Ober
 * @package Widgets
 * @see Phorm,Fields
 **/

/**
 * PhormWidget
 * 
 * The base class of all HTML form widgets.
 * @author Jeff Ober
 * @package Widgets
 **/
class PhormWidget
{
    /**
     * Serializes an array of key=>value pairs as an HTML attributes string.
     * @author Jeff Ober
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize_attributes(array $attributes=array())
    {
        $attr = array();
        foreach($attributes as $key => $val)
            $attr[] = sprintf('%s="%s"', $key, $val);
        return implode(' ', $attr);
    }
    
    /**
     * Serializes the widget as an HTML form input.
     * @author Jeff Ober
     * @param string $value the form widget's value
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        return sprintf('<input value="%s" %s />', $value, $this->serialize_attributes($attributes));
    }
    
    /**
     * Casts a value to a string and encodes it for HTML output.
     * @author Jeff Ober
     * @param mixed $str
     * @return a decoded string
     **/
    protected function clean_string($str)
    {
        return htmlentities((string)$str);
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    public function html($value, array $attributes=array())
    {
        $value = htmlentities( (string)$value );
        foreach ($attributes as $key => $val)
            $attributes[htmlentities( (string)$key )] = htmlentities( (string)$val );
        return $this->serialize($value, $attributes);
    }
}

/**
 * FileWidget
 * 
 * A file upload field. Requires that the form have enctype="multipart/form-data"
 * set to function.
 * @author Jeff Ober
 * @package Widgets
 **/
class FileWidget extends PhormWidget
{
    /**
     * Stores an array of valid mime types.
     **/
    private $types;
    
    /**
     * @author Jeff Ober
     * @param array $valid_mime_types e.g. array("image/jpeg", "image/jpg", "image/png", "image/gif")
     * @return null
     **/
    public function __construct(array $valid_mime_types)
    {
        $this->types = $valid_mime_types;
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'file';
        $attributes['accept'] = implode(',', $this->types);
        return parent::serialize($value, $attributes);
    }
}

/**
 * CharWidget
 * 
 * A basic text input field.
 * @author Jeff Ober
 * @package Widgets
 **/
class CharWidget extends PhormWidget
{
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'text';
        return parent::serialize($value, $attributes);
    }
}

/**
 * HiddenWidget
 * 
 * A hidden text field.
 * @author Jeff Ober
 * @package Widgets
 **/
class HiddenWidget extends PhormWidget
{
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'hidden';
        return parent::serialize($value, $attributes);
    }
}

/**
 * PasswordWidget
 * 
 * A password field.
 * @author Jeff Ober
 * @package Widgets
 **/
class PasswordWidget extends PhormWidget
{
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'password';
        return parent::serialize($value, $attributes);
    }
}

/**
 * TextWidget
 * 
 * A textarea.
 * @author Jeff Ober
 * @package Widgets
 **/
class TextWidget extends PhormWidget
{
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        return sprintf('<textarea %s>%s</textarea>', $this->serialize_attributes($attributes), $value);
    }
}

/**
 * RadioWidget
 * 
 * A radio button.
 * @author Jeff Ober
 * @package Widgets
 **/
class RadioWidget extends PhormWidget
{
    /**
     * Stores whether or not the field is checked.
     **/
    private $checked;
    
    /**
     * @author Jeff Ober
     * @param boolean $checked whether the field is initially checked
     * @return null
     **/
    public function __construct($checked=false)
    {
        $this->checked = $checked;
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'radio';
        if ($this->checked) $attributes['checked'] = 'checked';
        return parent::serialize($value, $attributes);
    }
}

/**
 * CheckboxWidget
 * 
 * A checkbox field.
 * @author Jeff Ober
 * @package Widgets
 **/
class CheckboxWidget extends PhormWidget
{
    /**
     * Stores whether or not the field is checked.
     **/
    private $checked;
    
    /**
     * @author Jeff Ober
     * @param boolean $checked whether the field is initially checked
     * @return null
     **/
    public function __construct($checked=false)
    {
        $this->checked = $checked;
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'checkbox';
        if ($this->checked) $attributes['checked'] = 'checked';
        return parent::serialize($value, $attributes);
    }
}

/**
 * OptionGroupWidget
 * 
 * A compound widget made up of multiple CheckboxWidgets.
 * @author Jeff Ober
 * @package Widgets
 * @see CheckboxWidget
 **/
class OptionGroupWidget extends PhormWidget
{
    /**
     * The options for this field as an array of actual=>display values.
     **/
    private $options;
    
    /**
     * @author Jeff Ober
     * @param array $options the options as an array of actual=>display values
     * @return null
     **/
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    /**
     * @author Jeff Ober
     * @param array $value an array of the field's values
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value 
     **/
    public function html($value, array $attributes=array())
    {
        if (is_null($value)) $value = array();
        
        foreach ($attributes as $key => $val)
            $attributes[htmlentities( (string)$key )] = htmlentities( (string)$val );
        
        return $this->serialize($value, $attributes);
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's value attribute
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $html = "";
        foreach ($this->options as $actual => $display)
        {
            $option = new CheckboxWidget( in_array($actual, $value) );
            $html .= sprintf('%s %s', htmlentities($display), $option->html($actual, $attributes));
        }
        
        return $html;
    }
}

/**
 * SelectWidget
 * 
 * A select widget (drop-down list.)
 * @author Jeff Ober
 * @package Widgets
 **/
class SelectWidget extends PhormWidget
{
    /**
     * The choices for this field as an array of actual=>display values.
     **/
    private $choices;
    
    /**
     * @author Jeff Ober
     * @param array $choices the choices as an array of actual=>display values
     * @return null
     **/
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param mixed $value the form widget's selected value
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        $options = array();
        foreach($this->choices as $actual => $display)
        {
            $option_attributes = array('value' => $this->clean_string($actual));
            if ($actual == $value) $option_attributes['selected'] = 'selected';
            $options[] = sprintf('<option %s>%s</option>',
                $this->serialize_attributes($option_attributes),
                $this->clean_string($display));
        }
        
        return sprintf('<select %s>%s</select>',
            $this->serialize_attributes($attributes),
            implode($options));
    }
}

/**
 * SelectWidget
 * 
 * A select multiple widget.
 * @author Jeff Ober
 * @package Widgets
 **/
class MultiSelectWidget extends PhormWidget
{
    /**
     * The choices for this field as an array of actual=>display values.
     **/
    private $choices;
    
    /**
     * @author Jeff Ober
     * @param array $choices the choices as an array of actual=>display values
     * @return null
     **/
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }
    
    /**
     * @author Jeff Ober
     * @param array $value an array of the field's values
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value 
     **/
    public function html($value, array $attributes=array())
    {
        if (is_null($value)) $value = array();
        
        foreach ($attributes as $key => $val)
            $attributes[htmlentities( (string)$key )] = htmlentities( (string)$val );
        
        return $this->serialize($value, $attributes);
    }
    
    /**
     * Returns the field as serialized HTML.
     * @author Jeff Ober
     * @param array $value the form widget's values
     * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
     * @return string the serialized HTML
     **/
    protected function serialize($value, array $attributes=array())
    {
        if (is_null($value))
            $value = array();
        
        if (!is_array($value))
            $value = array($value);
        
        $options = array();
        foreach($this->choices as $actual => $display)
        {
            $option_attributes = array('value' => $this->clean_string($actual));
            if (in_array($actual, $value)) $option_attributes['selected'] = 'selected';
            $options[] = sprintf('<option %s>%s</option>',
                $this->serialize_attributes($option_attributes),
                $this->clean_string($display));
        }
        
        return sprintf('<select multiple="multiple" %s>%s</select>',
            $this->serialize_attributes($attributes),
            implode($options));
    }
}

?>
