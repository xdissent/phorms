<?php

/**
 * Phorms: HTML form widgets with validation
 * 
 * Phorms is a form library that provides a number of factory classes that
 * generate HTML form data. Forms are defined by extending the base abstract
 * Phorm class with a 'define_fields' method which, when called, defines the
 * form's fields as class attributes. See the examples directory for a sample
 * comment form. Phorms is loosely modeled on the Django forms library, to the
 * extent that PHP is able to do the kind of introspection that it does. If you
 * are familiar with Django forms, then the concepts in phorms should not be too
 * alien.
 *
 * @author Jeff Ober
 * @package default
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @example ../examples/comment_form.php A simple comment form
 * 
 **/
 
/**
 * Constant used to determine path of includes.
 **/
define('PHORMS_ROOT', dirname(__FILE__) . '/');

/**
 * Widget classes used to serialize form elements.
 **/
require_once(PHORMS_ROOT . 'widgets.php');
/**
 * Various helper types.
 **/
require_once(PHORMS_ROOT . 'types.php');
/**
 * Field classes used to import and export form data and to handle
 * form validation.
 **/
require_once(PHORMS_ROOT . 'fields.php');
/**
 * Field classes used to import and export form data and to handle
 * form validation.
 **/
require_once(PHORMS_ROOT . 'fieldsets.php');

/**
 * Phorm
 * 
 * The abstract Phorm class wraps all of the functionality of the form itself.
 * It is extended to created an HTML form. It specifies one abstract method:
 * 'define_fields', which must set an attribute for each field in the form.
 * Fields must be descendents of the PhormField class.
 *
 * @author Jeff Ober
 * @see fields.php
 * @example ../examples/comment_form.php A simple comment form
 **/
abstract class Phorm
{
    /**
     * Causes the form to use $_GET as its data source.
     **/
    const GET = 0;
    /**
     * Causes the form to use $_POST as its data source.
     **/
    const POST = 1;
    
    /**
     * The form's method. Determines which superglobal array to use as the data
     * source.
     **/
    private $method = Phorm::GET;
    /**
     * If true, $_FILES is included in the form data. Makes possible file fields.
     **/
    private $multi_part = false;
    /**
     * True when the form has user-submitted data.
     **/
    private $bound = false;
    /**
     * A copy of the superglobal data array merged with any default field values
     * provided during class instantiation.
     * @see Phorm::__construct()
     **/
    private $data;
    /**
     * Private field storage.
     **/
    private $fields = array();
    /**
     * Private storage to collect error messages. Stored as $field_name => $msg.
     **/
    private $errors = array();
    /**
     * Private storage for cleaned field values.
     **/
    private $clean;
    /**
     * Memoized return value of the initial is_valid call.
     * @see Phorm::is_valid()
     **/
    private $valid;
    
    /**
     * @param Phorm::GET|Phorm::POST $method whether to use GET or POST
     * @param boolean $multi_part true if this form accepts files
     * @param array $data initial/default data for form fields (e.g. array('first_name'=>'enter your name'))
     * @return void
     * @author Jeff Ober
     **/
    public function __construct($method=Phorm::GET, $multi_part=false, $data=array())
    {
        $this->multi_part = $multi_part;
        if ($this->multi_part && $method != Phorm::POST)
        {
            $method = Phorm::POST;
            trigger_error('Multi-part form method changed to POST.', E_USER_WARNING);
        }
        
        // Set up fields
        $this->define_fields();
        $this->fields = $this->find_fields();
        
        // Find submitted data, if any
        switch ($method)
        {
            case Phorm::GET:
            $user_data = $_GET;
            $this->method = $method;
            break;
            
            case Phorm::POST:
            $user_data = $_POST;
            $this->method = $method;
            break;
            
            default:
            $user_data = array();
            $this->method = Phorm::GET;
        }
        
        // Determine if this form is bound (depends on defined fields)
        $this->bound = $this->check_if_bound($user_data);
        
        // Merge user data over the default data (if any)
        $this->data = array_merge($data, $user_data);
        
        // Set the fields' data
        $this->set_data();
    }
    
    /**
     * Pre-processes user submitted data by checking that each field has a
     * corresponding value. This prevents the default data from being used with
     * a "missing" field value, such as is the case with a checkbox or radio
     * field that is unchecked.
     * @author Jeff Ober
     * @param array $data a superglobal data array (e.g. $_GET or $_POST)
     * @return array the processed data
     **/
    // private function pre_process_data(array $data)
    // {
    //  foreach(array_keys($this->fields) as $name)
    //      if ( !array_key_exists($name, $data) )
    //          $data[$name] = '';
    //  return $data;
    // }
    
    /**
     * Abstract method that sets the Phorm's fields as class attributes.
     * @return null
     * @author Jeff Ober
     **/
    abstract protected function define_fields();
    
    /**
     * Returns true if any of the field's names exist in the source data (or
     * in $_FILES if this is a multi-part form.)
     * @return boolean
     * @author Jeff Ober
     **/
    private function check_if_bound(array $data)
    {
        foreach ($this->fields as $name => $field)
            if (array_key_exists($name, $data) || ($this->multi_part && array_key_exists($name, $_FILES)))
                return true;
        return false;
    }
    
    /**
     * Internal method used by the constructor to find all of the fields in the
     * class after the child's 'define_fields' is called. Returns an array of
     * the field instances.
     * @return array the field instances
     * @author Jeff Ober
     **/
    private function find_fields()
    {
        $found = array();
        foreach (array_keys(get_object_vars($this)) as $name)
        {
            if ($this->$name instanceof PhormField)
            {
                $name = htmlentities($name);
                $id = sprintf('id_%s', $name);
                
                $this->$name->set_attribute('id', $id);
                $this->$name->set_attribute('name', ($this->$name->multi_field) ? sprintf('%s[]', $name) : $name);
                
                $found[$name] =& $this->$name;
            }
        }
        return $found;
    }
    
    /**
     * Sets the value of each field from the proper superglobal data array.
     * @return null
     * @author Jeff Ober
     **/
    private function set_data()
    {
        foreach ($this->fields as $name => &$field)
            if (array_key_exists($name, $this->data))
                $field->set_value($this->data[$name]);
    }
    
    /**
     * Returns an associative array of the imported form data on a bound, valid
     * form. Returns null if the form is not yet bound or if the form is not
     * valid.
     * @return array|null
     * @author Jeff Ober
     **/
    public function cleaned_data()
    {
        return $this->clean;
    }
    
    /**
     * Returns true if the form is bound (i.e., there is data in the appropriate
     * superglobal array.)
     * @return boolean
     * @author Jeff Ober
     **/
    public function is_bound()
    {
        return $this->bound;
    }
    
    /**
     * Returns true if the form has errors.
     * @author Jeff Ober
     * @return boolean
     **/
    public function has_errors()
    {
        return count($this->errors) > 0;
    }
    
    /**
     * Returns the list of errors.
     * @author Jeff Ober
     * @return array error messages
     **/
    public function get_errors()
    {
        return $this->errors;
    }
    
    /**
     * Returns true if all fields' data pass validation tests.
     * @param boolean $reprocess if true (default: false), call all validators again
     * @return boolean
     * @author Jeff Ober
     **/
    public function is_valid($reprocess=false)
    {
        if ( $reprocess || is_null($this->valid) )
        {
            if ( $this->is_bound() )
            {
                foreach($this->fields as $name => &$field)
                    if ( !$field->is_valid($reprocess) )
                        $this->errors[$name] = $field->get_errors();
                $this->valid = ( count($this->errors) === 0 );
            }
            if ( $this->valid && $this->is_bound() ) $this->clean_data();
        }
        return $this->valid;
    }
    
    /**
     * Processes each field's data in turn, calling it's get_value method to
     * access its "cleaned" data.
     * @return null
     * @author Jeff Ober
     **/
    private function clean_data()
    {
        $this->clean = array();
        foreach($this->fields as $name => &$field)
            $this->clean[$name] = $field->get_value();
    }
    
    /**
     * Returns an iterator that returns each field instance in turn.
     * @return Iterator
     * @author Jeff Ober
     **/
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }
    
    /**
     * Returns the form's opening HTML tag.
     * @param string $target the form target ($_SERVER['PHP_SELF'] by default)
     * @return string the form's opening tag
     * @author Jeff Ober
     **/
    public function open($target=null)
    {
        if ( is_null($target) ) $target = $_SERVER['PHP_SELF'];
        
        switch ($this->method)
        {
            case Phorm::GET:
            $method = "GET";
            break;
            
            case Phorm::POST:
            $method = "POST";
            break;
            
            default:
            $method = "GET";
        }
        
        return sprintf('<form method="%s" action="%s"%s>',
            $method,
            htmlentities((string)$target),
            ($this->multi_part) ? ' enctype="multipart/form-data"' : ''
        );
    }
    
    /**
     * Returns the form's closing HTML tag.
     * @return string the form's closing tag
     * @author Jeff Ober
     **/
    public function close()
    {
        return '</form>';
    }
    
    /**
     * Returns a string of all of the form's fields' HTML tags as a table.
     * @return string the HTML form
     * @author Jeff Ober
     * @see Phorm::as_table()
     **/
    public function __toString()
    {
        return $this->as_table();
    }
    
    /**
     * Returns the form fields as a series of HTML table rows. Does not include
     * the table's opening and closing tags, nor the table's tbody tags.
     * @return string the HTML form
     * @author Jeff Ober
     **/
    public function as_table()
    {
        $elts = array();
        foreach ($this->fields as $name => $field)
        {
            $label = $field->label();
            if ($label !== '')
                $elts[] = sprintf('<tr><th>%s:</th><td>%s</td></tr>', $field->label(), $field); 
            else
                $elts[] = strval($field);
        }
        return implode($elts);
    }
    
    /**
     * Returns the form fields as a series of list items. Does not include the
     * list's opening and closing tags.
     * @return string the HTML form
     * @author Jeff Ober
     **/
    public function as_list()
    {
        $elts = array();
        foreach ($this->fields as $name => $field)
        {
            $label = $field->label();
            if ($label !== '')
                $elts[] = sprintf('<li>%s: %s</li>', $field->label(), $field);
            else
                $elts[] = strval($field);
        }
        return implode($elts);
    }
}


/**
 * FieldsetPhorm
 * 
 * The abstract FieldsetPhorm class is a subclass of Phorm. It additionally
 * specifies one abstract method: 'define_fieldsets', which must set the
 * 'fieldsets' attribute. It should be an array of Fieldset instances to
 * use in the form.
 *
 * @author Greg Thornton
 * @see fieldsets.php
 * @example ../examples/comment_form.php A simple comment form
 **/
abstract class FieldsetPhorm extends Phorm
{
    public function __construct($method=Phorm::GET, $multi_part=false, $data=array())
    {
        parent::__construct($method, $multi_part, $data);
        $this->define_fieldsets();
    }

    /**
     * Returns the form fields as a series of HTML table rows. Does not include
     * the table's opening and closing tags, nor the table's tbody tags.
     * @return string the HTML form
     * @author Greg Thornton
     **/
    public function as_table()
    {
        $elts = array();
        foreach ($this->fieldsets as $fieldset)
        {
            $elts[] = sprintf('<tr><td colspan="2"><fieldset><legend>%s</legend><table>', $fieldset->label);
            foreach ($fieldset->field_names as $field_name) {
                $field = $this->$field_name;
                $label = $field->label();

                if ($label !== '')
                    $elts[] = sprintf('<tr><th>%s:</th><td>%s</td></tr>', $label, $field);
                else
                    $elts[] = strval($field);
            }
            $elts[] = '</table></fieldset></td></tr>';
        }
        return implode($elts, "\n");
    }
}

?>