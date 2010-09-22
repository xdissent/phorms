<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field
 *
 * Abstract class from which all other field classes are derived.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
abstract class Phorm_Field
{

	/**
	 * The field's text label.
	 * @var string
	 */
	public $label;
	/**
	 * Store's the field's value. Set during validation.
	 * @var string
	 */
	private $value;
	/**
	 * Array of callbacks used to validate field data. May be either a string
	 * denoting a function or an array of array(instance, string method) to use
	 * a class instance method.
	 * @var array
	 */
	private $validators;
	/**
	 * Associative array of key/value pairs representing HTML attributes of the field.
	 * @var array
	 */
	private $attributes;
	/**
	 * Array storing errors generated during field validation.
	 * @var array
	 */
	private $errors;
	/**
	 * Storage of the "cleaned" field value.
	 */
	private $imported;
	/**
	 * Help text for the field. This is printed out with the field HTML.
	 * @var string
	 */
	private $help_text = '';
	/**
	 * If true, this field uses multiple field widgets.
	 * @see widgets.php
	 * @var boolean
	 */
	public $multi_field = false;
	/**
	 * Stores the result of field validation to prevents double-validation.
	 * @var boolean
	 */
	private $valid;

	/**
	 * @param string $label the field's label
	 * @param array $validators callbacks used to validate field data
	 * @param array $attributes an assoc of key/value pairs representing HTML attributes
	 * @return null
	 */
	public function __construct($label, array $validators=array(), array $attributes=array(), $lang='en')
	{
		if( !isset($attributes['class']) )
		{
			$attributes['class'] = strtolower(get_class($this));
		}
		else
		{
			$attributes['class'] .= ' '.strtolower(get_class($this));
		}
		
		$this->label = (string) $label;
		$this->attributes = $attributes;
		$this->validators = $validators;
		$this->lang = new Phorm_Language($lang);
	}

	/**
	 * Sets the value of the field.
	 *
	 * @param mixed $value the field's value
	 * @return null
	 */
	public function set_value($value)
	{
		$this->value = $value;
	}

	/**
	 * Returns the "cleaned" value of the field.
	 *
	 * @return mixed the field's "cleaned" value
	 */
	public function get_value()
	{
		return $this->imported;
	}

	/**
	 * Returns the "raw" value of the field.
	 *
	 * @author Aaron Stone <aaron@serendipity.cx>
	 * @return mixed the field's raw value (unsanitized)
	 */
	public function get_raw_value()
	{
		return $this->value;
	}

	/**
	 * Sets an HTML attribute of the field.
	 *
	 * @param string $key the attribute name
	 * @param string $value the attribute's value
	 * @return null
	 */
	public function set_attribute($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * Returns the value of an HTML attribute or null if not set.
	 *
	 * @param string $key the attribute name to look up
	 * @return string|null the attribute's value or null if not set
	 */
	public function get_attribute($key)
	{
		if( array_key_exists($key, $this->attributes) )
		{
			return $this->attributes[$key];
		}
		return null;
	}

	/**
	 * Returns a list of errors generated during validation. If the field is not
	 * yet validated, returns null.
	 *
	 * @return array|null
	 */
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 * Adds to the error list.
	 *
	 * @author Aaron Stone <aaron@serendipity.cx>
	 * @return null
	 */
	public function add_error($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Returns an HTML string containing the field's help text.
	 * If provided with $text it assigns help text to the field.
	 *
	 * @param string $text the help text
	 * @return null|string
	 */
	public function help_text($text='')
	{
		if( !empty($text) )
		{
			$this->help_text = $text;
		}
		elseif( !empty($this->help_text) )
		{
			return '<span class="phorm_help">'.htmlentities($this->help_text).'</span>';
		}
	}

	/**
	 * Returns the HTML field label.
	 *
	 * @param boolean $tag determines whether or not label is wrapped in <label> HTML (defaults to TRUE)
	 * @return string the HTML label tag
	 */
	public function label($tag=TRUE)
	{
		if($tag)
		{
			return sprintf('<label for="%s">%s</label>', (string) $this->get_attribute('id'), $this->label);
		}
		return $this->label;
	}

	/**
	 * Returns the field's tag as HTML.
	 *
	 * @return string the field as HTML
	 */
	public function html()
	{
		$widget = $this->get_widget();
		return $widget->html($this->value, $this->attributes);
	}

	/**
	 * Returns the field's errors, optionally wrapped in a div
	 *
	 * @param boolean $tag determines whether or not to wrap each error message in a <div> (defaults to TRUE)
	 * @return string the field errors as an unordered list
	 */
	public function errors($tag=TRUE)
	{
		$elts = array();
		if( is_array($this->errors) && !empty($this->errors) )
		{
			foreach( $this->errors as $valid => $error )
			{
				if($tag)
				{
					$elts[] = sprintf('<div class="validation-advice" id="advice-%s-%s">%s</div>', $error[0], (string) $this->get_attribute('id'), $this->lang->{$error[1]});
				}
				else
				{
					$elts[] = $this->lang->{$error[1]};
				}
			}
			return implode("\n", $elts);
		}
		return (empty($elts))?'':$elts;
	}

	/**
	 * Serializes the field to HTML.
	 *
	 * @return string the field's complete HTMl representation.
	 */
	public function __toString()
	{
		return $this->html().$this->help_text.$this->errors();
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
	 * @return boolean true if the field's value is valid
	 * @see PhormField::$valid,PhormField::$imported,PhormField::$validators,PhormField::$errors
	 */
	public function is_valid($reprocess=false)
	{
		if( $reprocess || is_null($this->valid) )
		{
			// Pre-process value
			$value = $this->prepare_value($this->value);

			$this->errors = array();
			$v = $this->validators;

			foreach( $v as $k => $f )
			{
				try
				{
					if ($f == 'required') { //special case -- available to all field types, and $this->validate() isn't even called if value is empty
						$this->validate_required_field($value);
					} else {
						call_user_func($f, $value);
					}
				}
				catch( Phorm_ValidationError $e )
				{
					$rule_name = is_array($f) ? $f[1] : $f; //handles both string (function name) and array (instance, function name)
					$this->errors[] = array( $rule_name, $this->lang->{$e->getMessage()} );
				}
			}

			if( $value !== '' )
			{
				try
				{
					$this->validate($value);
				}
				catch( Phorm_ValidationError $e )
				{
					$this->errors[] = array( strtolower(get_class($this)), $this->lang->{$e->getMessage()} );
				}
			}

			if( $this->valid = empty($this->errors) )
			{
				$this->imported = $this->import_value($value);
			}
		}
		return $this->valid;
	}

	/**
	 * Pre-processes a value for validation, handling magic quotes if used.
	 *
	 * @param string $value the value from the form array
	 * @return string the pre-processed value
	 */
	public function prepare_value($value)
	{
		return ( get_magic_quotes_gpc() ) ? stripslashes($value) : $value;
	}

	/**
	 * Defined in derived classes; must return an instance of PhormWidget.
	 *
	 * @return PhormWidget the field's widget
	 * @see PhormWidget
	 */
	abstract protected function get_widget();

	/**
	 * Raises a Phorm_ValidationError if $value is invalid.
	 *
	 * @param string|mixed $value (may be mixed if prepare_value returns a non-string)
	 * @throws ValidationError
	 * @return null
	 * @see ValidationError
	 */
	public function validate($value)
	{
		return filter_var($value, FILTER_SANITIZE_STRING);
	}

	/**
	 * Returns the field's "imported" value, if any processing is required. For
	 * example, this function may be used to convert a date/time field's string
	 * into a unix timestamp or a numeric string into an integer or float.
	 *
	 * @param string|mixed $value the pre-processed string value (or mixed if prepare_value returns a non-string)
	 * @return mixed
	 */
	abstract public function import_value($value);
	
	/**
	 * Validates that the value isn't null or an empty string.
	 * This is a built-in validation rule available to all fields
	 * (we have hard-coded logic in the is_valid() function
	 *  to call this if 'required' exists in the validation array).
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate_required_field($value)
	{
		if ($value == '' || is_null($value))
		{
			throw new Phorm_ValidationError('validation_required');
		}
	}
}