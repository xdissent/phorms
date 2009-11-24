<?php
/**
 * Form
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License that is available
 * through the world-wide-web at the following URI: 
 * http://www.opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to obtain it 
 * through the web, please send a note to the author and a copy will be provided
 * for you.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Forms
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
 
/**
 * Phorms_Forms_Form
 * 
 * The abstract Phorms_Forms_Form class wraps all of the functionality of 
 * the form itself. It is extended to create an HTML form. It specifies 
 * one abstract method: 'defineFields', which must set an attribute for 
 * each field in the form. Fields must be descendents of the 
 * 'Phorms_Forms_FormField' class.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Forms
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 *
 * @abstract
 *
 * @todo Remove GET and POST constants in favor of actual arrays.
 */
abstract class Phorms_Forms_Form
{
    /**
     * Causes the form to use $_GET as its data source.
     */
    const GET = 0;

    /**
     * Causes the form to use $_POST as its data source.
     */
    const POST = 1;
    
    /**
     * Causes the form to use the static $test_data variable as its data source.
     */
    const TEST = 2;
    
    /**
     * The form's method. Determines which superglobal array to use as the data
     * source.
     *
     * @var    Phorms_Forms_Form::GET|Phorms_Forms_Form::POST
     * @access protected
     */
    protected $method = Phorms_Forms_Form::GET;
    
    /**
     * If true, $_FILES is included in the form data. Makes possible file fields.
     *
     * @var    boolean
     * @access protected
     */
    protected $multi_part = false;
    
    /**
     * True when the form has user-submitted data.
     *
     * @var    boolean
     * @access protected
     */
    protected $bound = false;
    
    /**
     * A copy of the superglobal data array merged with any default field values
     * provided during class instantiation.
     *
     * @var    array
     * @access protected
     */
    protected $data;
    
    /**
     * Protected field storage.
     *
     * @var    array
     * @access protected
     */
    protected $fields = array();
    
    /**
     * Protected storage to collect error messages. Stored as $field_name => $msg.
     *
     * @var    array
     * @access protected
     */
    protected $errors = array();
    
    /**
     * Protected storage for cleaned field values.
     *
     * @var    array
     * @access protected
     */
    protected $clean;
    
    /**
     * Memoized return value of the initial is_valid call.
     *
     * @var    array
     * @access protected
     */
    protected $valid;
    
    /**
     * Test form data.
     *
     * @var    array
     * @static
     * @access public
     */
    public static $test_data;
    
    /**
     * The Form constructor.
     *
     * @param mixed   $method     Whether to use GET or POST for the form data.
     * @param boolean $multi_part True if this form accepts files.
     * @param array   $data       Initial/default data for form fields (e.g. 
     *                            array('first_name'=>'enter your name')).
     *
     * @access public
     * @return void
     */
    public function __construct($method=Phorms_Forms_Form::GET, 
    $multi_part=false, $data=array()) {
    
        $this->multi_part = $multi_part;
        if ($this->multi_part && $method != Phorms_Forms_Form::POST) {
            $method = Phorms_Forms_Form::POST;
            trigger_error('Multi-part form method changed to POST.', E_USER_WARNING);
        }
        
        // Set up fields
        $this->defineFields();
        $this->fields = $this->findFields();
        
        // Find submitted data, if any
        switch ($method) {
        case Phorms_Forms_Form::GET:
            $user_data = $_GET;
            $this->method = $method;
            break;
        
        case Phorms_Forms_Form::POST:
            $user_data = $_POST;
            $this->method = $method;
            break;

        /**
         * Allow test data, defined as a static member variable.
         */
        case Phorms_Forms_Form::TEST:
            $user_data = static::$test_data;
            $this->method = Phorms_Forms_Form::GET;
            break;
        
        default:
            $user_data = array();
            $this->method = Phorms_Forms_Form::GET;
        }
        
        // Determine if this form is bound (depends on defined fields)
        $this->bound = $this->hasFieldsInData($user_data);
        
        // Merge user data over the default data (if any)
        $this->data = array_merge($data, $user_data);
        
        // Set the fields' data
        $this->setData();
    }
    
    /**
     * Abstract method that sets the Form's fields as class attributes.
     *
     * @access   protected
     * @abstract
     * @return   void
     */
    abstract protected function defineFields();
    
    /**
     * Returns true if the data has keys that match any defined field names.
     *
     * @param array $data The data to check for boundness.
     *
     * @access protected
     * @return boolean
     */
    protected function hasFieldsInData(array $data)
    {
        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $data) 
                || ($this->multi_part && array_key_exists($name, $_FILES))
            ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Internal method used by the constructor to find all of the fields in the
     * class after the child's 'defineFields' is called. Returns an array of
     * the field instances.
     *
     * @access protected
     * @return array
     */
    protected function findFields()
    {
        $found = array();
        foreach (array_keys(get_object_vars($this)) as $name) {
            if ($this->$name instanceof Phorms_Fields_Field) {
                $name = htmlentities($name);
                $id = sprintf('id_%s', $name);
                
                if ($this->$name->multi_field) {
                    $this->$name->setAttribute('name', sprintf('%s[]', $name));
                } else {
                    $this->$name->setAttribute('name', $name);
                }
                
                $this->$name->setAttribute('id', $id);
                
                $found[$name] =& $this->$name;
            }
        }
        return $found;
    }
    
    /**
     * Sets the value of each field from the proper superglobal data array.
     *
     * @access protected
     * @return void
     */
    protected function setData()
    {
        foreach ($this->fields as $name => &$field) {
            if (array_key_exists($name, $this->data)) {
                $field->setValue($this->data[$name]);
            }
        }
    }
    
    /**
     * Returns an associative array of the imported form data on a bound, valid
     * form. Returns null if the form is not yet bound or if the form is not
     * valid.
     *
     * @access public
     * @return array|void
     */
    public function cleanedData()
    {
        return $this->clean;
    }
    
    /**
     * Returns true if the form is bound (i.e., there is data in the appropriate
     * superglobal array.)
     *
     * @access public
     * @return boolean
     */
    public function isBound()
    {
        return $this->bound;
    }
    
    /**
     * Returns true if the form has errors.
     *
     * @access public
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
    
    /**
     * Returns the list of errors.
     *
     * @access public
     * @return array Error messages for this form.
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Returns true if all fields' data pass validation tests.
     *
     * @param boolean $reprocess If true (default: false), call all validators 
     *                           again.
     *
     * @access public
     * @return boolean
     */
    public function isValid($reprocess=false)
    {
        if ($reprocess || is_null($this->valid)) {
            if ($this->isBound()) {
                foreach ($this->fields as $name => &$field) {
                    if (!$field->isValid($reprocess)) {
                        $this->errors[$name] = $field->getErrors();
                    }
                }
                $this->valid = (count($this->errors) === 0);
            }
            if ($this->valid && $this->isBound()) {
                $this->cleanData();
            }
        }
        return $this->valid;
    }
    
    /**
     * Processes each field's data in turn, calling it's get_value method to
     * access its "cleaned" data.
     *
     * @access protected
     * @return null
     */
    protected function cleanData()
    {
        $this->clean = array();
        foreach ($this->fields as $name => &$field) {
            $this->clean[$name] = $field->getValue();
        }
    }
    
    /**
     * Returns an iterator that returns each field instance in turn.
     *
     * @access public
     * @return Iterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }
    
    /**
     * Returns the form's opening HTML tag.
     *
     * @param string $target The form target ($_SERVER['PHP_SELF'] by default).
     *
     * @access public
     * @return string
     */
    public function open($target=null)
    {
        if (is_null($target)) {
            $target = $_SERVER['PHP_SELF'];
        }
        
        switch ($this->method)
        {
        case Phorms_Forms_Form::GET:
            $method = "GET";
            break;
        
        case Phorms_Forms_Form::POST:
            $method = "POST";
            break;
        
        default:
            $method = "GET";
        }
        
        return sprintf(
            '<form method="%s" action="%s"%s>',
            $method,
            htmlentities((string)$target),
            ($this->multi_part) ? ' enctype="multipart/form-data"' : ''
        );
    }
    
    /**
     * Returns the form's closing HTML tag.
     *
     * @access public
     * @return string the form's closing tag
     */
    public function close()
    {
        return '</form>';
    }
    
    /**
     * Returns a string of all of the form's fields' HTML tags as a table.
     *
     * @access public
     * @return string the HTML form
     */
    public function __toString()
    {
        return $this->asTable();
    }
    
    /**
     * Returns the form fields as a series of HTML table rows. Does not include
     * the table's opening and closing tags, nor the table's tbody tags.
     *
     * @access public
     * @return string the HTML form
     */
    public function asTable()
    {
        $elts = array();
        foreach ($this->fields as $name => $field) {
            $label = $field->label();
            if ($label !== '') {
                $elts[] = sprintf(
                    '<tr><th>%s:</th><td>%s</td></tr>',
                    $field->label(),
                    $field
                ); 
            } else {
                $elts[] = strval($field);
            }
        }
        return implode($elts);
    }
    
    /**
     * Returns the form fields as a series of list items. Does not include the
     * list's opening and closing tags.
     *
     * @access public
     * @return string the HTML form
     */
    public function asList()
    {
        $elts = array();
        foreach ($this->fields as $name => $field) {
            $label = $field->label();
            if ($label !== '') {
                $elts[] = sprintf('<li>%s: %s</li>', $field->label(), $field);
            } else {
                $elts[] = strval($field);
            }
        }
        return implode($elts);
    }
}
?>