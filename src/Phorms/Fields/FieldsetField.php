<?php
/**
 * Fieldset Field
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
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
 
/**
 * Fiedset Field
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 *
 * @abstract
 */
class Phorms_Fields_FieldsetField extends Phorms_Fields_Field
{
    /**
     * The field's text label.
     *
     * @var    string
     * @access protected
     */
    protected $label;
    
    /**
     * Store's the field's value. Set during validation.
     * 
     * @var    mixed
     * @access protected
     */
    protected $value;
    
    /**
     * Array of callbacks used to validate field data. May be either a string
     * denoting a function or an array of array(instance, string method) to use
     * a class instance method.
     *
     * @var    array
     * @access protected
     */
    protected $validators;
    
    /**
     * Associative array of key/value pairs representing HTML attributes of 
     * the field.
     *
     * @var    array
     * @access protected
     */
    protected $attributes;
    
    /**
     * Array storing errors generated during field validation.
     *
     * @var    array
     * @access protected
     */
    protected $errors;
    
    /**
     * Storage of the "cleaned" field value.
     *
     * @var    mixed
     * @access protected
     */
    protected $imported;
    
    /**
     * Help text for the field. This is printed out with the field HTML.
     *
     * @var    string
     * @access protected
     */
    protected $help_text = "";
    
    /**
     * If true, this field uses multiple field widgets.
     *
     * @var    boolean
     * @access public
     */
    public $multi_field = false;
    
    /**
     * Stores the result of field validation to prevent double-validation.
     *
     * @var    mixed
     * @access protected
     */
    protected $valid;
    
    /**
     * The field constructor.
     *
     * @param string $label      The field's label.
     * @param string $help_text  The field's help text.
     * @param array  $validators Callbacks used to validate field data.
     * @param array  $attributes An assoc of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='',
    array $validators=array(), array $attributes=array()) {
        $this->label = (string)$label;
        $this->help_text = (string)$help_text;
        $this->attributes = $attributes;
        $this->validators = $validators;
    }
    
    /**
     * Assigns help text to the field.
     *
     * @param string $text The help text.
     *
     * @access public
     * @return void
     */
    public function setHelpText($text)
    {
        $this->help_text = (string)$text;
    }
    
    /**
     * Sets the value of the field.
     *
     * @param mixed $value The field's value.
     *
     * @access public
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * Returns the "cleaned" value of the field.
     *
     * @access public
     * @return mixed
     */
    public function getValue()
    {
        return $this->imported;
    }
    
    /**
     * Sets an HTML attribute of the field.
     *
     * @param string $key   The attribute's name.
     * @param string $value The attribute's value.
     *
     * @access public
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Returns the value of an HTML attribute or null if not set.
     *
     * @param string $key the attribute name to look up
     *
     * @access public
     * @return string|void
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return null;
    }
    
    /**
     * Returns a list of errors generated during validation. If the field is not
     * yet validated, returns null.
     *
     * @access public
     * @return array|void
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Returns an HTML string containing the field's help text.
     *
     * @access public
     * @return string
     */
    public function getHelpText()
    {
        return sprintf(
            '<p class="help">%s</p>', 
            htmlentities($this->help_text)
        );
    }
    
    /**
     * Returns the HTML field label.
     *
     * @access public
     * @return string
     */
    public function label()
    {
        return sprintf(
            '<label for="%s">%s</label>', 
            (string)$this->getAttribute('id'),
            $this->label
        );
    }
    
    /**
     * Returns the field's tag as HTML.
     *
     * @access public
     * @return string
     */
    public function html()
    {
        $widget = $this->getWidget();
        $attr = $this->attributes;
        return $widget->html($this->value, $this->attributes);
    }
    
    /**
     * Returns the field's errors as an unordered list with the class 
     * "phorm_error".
     *
     * @access public
     * @return string
     */
    public function errors()
    {
        $elts = array();
        if (is_array($this->errors) && count($this->errors) > 0) {
            foreach ($this->errors as $error) {
                $elts[] = sprintf('<li>%s</li>', $error);
            }
        }
        return sprintf('<ul class="phorm_error">%s</ul>', implode($elts));
    }
    
    /**
     * Serializes the field to HTML.
     *
     * @access public
     * @return string 
     */
    public function __toString()
    {
        return $this->html() . $this->getHelpText() . $this->errors();
    }
    
    /**
     * On the first call, calls each validator on the field value, and returns
     * true if each returned successfully, false if any raised a
     * ValidationError. On subsequent calls, returns the same value as the
     * initial call. If $reprocess is set to true (default: false), will
     * call each of the validators again. Stores the "cleaned" value of the
     * field on success.
     *
     * @param boolean $reprocess if true, ignores memoized result of initial call
     *
     * @access public
     * @return boolean
     */
    public function isValid($reprocess=false)
    {
        if ($reprocess || is_null($this->valid)) {
            // Pre-process value
            $value = $this->prepareValue($this->value);

            $this->errors = array();
            $v = $this->validators;

            foreach ($v as $f) {
                try {
                    call_user_func($f, $value);
                } catch (Phorms_Validation_Error $e) { 
                    $this->errors[] = $e->getMessage();
                }
            }
            
            if ($value !== '') {
                try {
                    $this->validate($value);
                } catch (Phorms_Validation_Error $e) { 
                    $this->errors[] = $e->getMessage(); 
                }
            }

            if ($this->valid = (count($this->errors) === 0)) {
                $this->imported = $this->importValue($value);
            }
        }
        return $this->valid;
    }
    
    /**
     * Pre-processes a value for validation, handling magic quotes if used.
     *
     * @param string $value The value from the form array.
     *
     * @access protected
     * @return string
     */
    protected function prepareValue($value)
    {
        return (get_magic_quotes_gpc()) ? stripslashes($value) : $value;
    }
    
    /**
     * Defined in derived classes; must return an instance of PhormWidget.
     *
     * @access   protected
     * @abstract
     * @return   PhormWidget
     */
    abstract protected function getWidget();
    
    /**
     * Raises a ValidationError if $value is invalid.
     *
     * @param string|mixed $value (may be mixed if prepare_value returns a 
     *                            non-string)
     *
     * @access   protected
     * @abstract 
     * @throws   Phorms_Validation_Error
     * @return   void
     */
    abstract protected function validate($value);
    
    /**
     * Returns the field's "imported" value, if any processing is required. For
     * example, this function may be used to convert a date/time field's string
     * into a unix timestamp or a numeric string into an integer or float.
     *
     * @param string|mixed $value The pre-processed string value (or mixed if 
     *                            prepare_value returns a non-string).
     *
     * @access   public
     * @abstract
     * @return   mixed
     */
    abstract public function importValue($value);
}
?>