<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
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
 * @package Phorms
 */

/**
 * Phorm
 *
 * The abstract Phorm class wraps all of the functionality of the form itself.
 * It is extended to created an HTML form. It specifies one abstract method:
 * 'define_fields', which must set an attribute for each field in the form.
 * Fields must be descendents of the PhormField class.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @see Field.class.php
 * @example ../examples/comment_form.php A simple comment form
 */
abstract class Phorm_Phorm
{

	/**
	 * The form's method. Determines which superglobal array to use as the data source.
	 *
	 * @var int
	 */
	private $method;

	/**
	 * If true, $_FILES is included in the form data. Makes possible file fields.
	 *
	 * @var boolean
	 */
	private $multi_part = false;

	/**
	 * True when the form has user-submitted data.
	 *
	 * @var boolean
	 */
	public $bound = false;

	/**
	 * A copy of the superglobal data array merged with any default field values
	 * provided during class instantiation.
	 *
	 * @see Phorm::__construct()
	 * @var array
	 */
	private $data;

	/**
	 * Private field storage.
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Private storage to collect error messages. Stored as $field_name => $msg.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Private storage for cleaned field values.
	 */
	private $clean;

	/**
	 * Memoized return value of the initial is_valid call.
	 *
	 * @see Phorm::is_valid()
	 * @var boolean
	 */
	private $valid;

	/**
	 * The language to use for error messages.
	 *
	 * @var Phorm_Language
	 */
	public $lang;

	/**
	 * @param string $method 'post' or 'get' (defaults to 'post')
	 * @param boolean $multi_part true if this form accepts files
	 * @param array $data initial/default data for form fields (e.g. array('first_name'=>'enter your name'))
	 * @param string $lang the language in which the phorm will respond
	 * @return void
	 */
	public function __construct($method='post', $multi_part=FALSE, $data=array(), $lang='en')
	{
		$this->multi_part = $multi_part;

		if( $this->multi_part && $method != 'post' )
		{
			$method = 'post';
			throw new Exception('Multi-part form method changed to POST.', E_USER_WARNING);
		}

		// Set up fields
		$this->define_fields();
		$this->fields = $this->find_fields();

		// Find submitted data, if any
		$method = strtolower($method);
		$this->method = $method;
		$user_data = ($this->method == 'post') ? $_POST : $_GET;

		// Determine if this form is bound (depends on defined fields)
		$this->bound = $this->check_if_bound($user_data);

		// Merge user data over the default data (if any)
		$this->data = array_merge($data, $user_data);

		// Set the fields' data
		$this->set_data();
		$this->lang = new Phorm_Language($lang);
	}

	/**
	 * Abstract method that sets the Phorm's fields as class attributes.
	 *
	 * @return null
	 */
	abstract protected function define_fields();

	/**
	 * Returns true if any of the field's names exist in the source data (or
	 * in $_FILES if this is a multi-part form.)
	 *
	 * @return boolean
	 */
	private function check_if_bound(array $data)
	{
		foreach( $this->fields as $name => $field )
		{
			if( array_key_exists($name, $data) || ($this->multi_part && array_key_exists($name, $_FILES)) )
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Internal method used by the constructor to find all of the fields in the
	 * class after the child's 'define_fields' is called. Returns an array of
	 * the field instances.
	 *
	 * @return array the field instances
	 */
	private function find_fields()
	{
		$found = array();
		foreach( array_keys(get_object_vars($this)) as $name )
		{
			if( $this->$name instanceof Phorm_Field )
			{
				$name = htmlentities($name);
				$id = sprintf('id_%s', $name);

				$this->$name->set_attribute('id', $id);
				$this->$name->set_attribute('name', ($this->$name->multi_field) ? sprintf('%s[]', $name) : $name);

				$found[$name] = & $this->$name;
			}
		}
		return $found;
	}

	/**
	 * Sets the value of each field from the proper superglobal data array.
	 *
	 * @return null
	 */
	private function set_data()
	{
		foreach( $this->fields as $name => &$field )
		{
			if( array_key_exists($name, $this->data) )
			{
				$field->set_value($this->data[$name]);
			}
		}
	}

	/**
	 * Returns an associative array of the imported form data on a bound, valid
	 * form. Returns null if the form is not yet bound or if the form is not
	 * valid. Calls each field's get_value method, caching the values in the
	 * Phorm instance. If reprocess is true, the cache is rebuilt.
	 *
	 * @author Aaron Stone
	 * @return array|null
	 */
	public function cleaned_data($reprocess=FALSE)
	{
		if( !$this->bound && !$this->is_valid() )
		{
			return NULL;
		}

		if( !is_array($this->clean) || $reprocess )
		{
			$this->clean = array();
			foreach( $this->fields as $name => &$field )
			{
				$this->clean[$name] = $field->get_value();
			}
		}

		return $this->clean;
	}
	
	/**
	 * Returns an array of the form's field objects
	 *
	 * @return array fields
	 */
	public function fields()
	{
		return $this->fields;
	}
	
	/**
	 * Returns true if the form has errors.
	 *
	 * @return boolean
	 */
	public function has_errors()
	{
		return !empty($this->errors);
	}

	/**
	 * Returns the list of errors.
	 *
	 * @return array error messages
	 */
	public function get_errors()
	{
		foreach( $this->fields as $name => &$field )
		{
			if( $errors = $field->get_errors() )
			{
				foreach($errors as $error)
				{
					$this->errors[$name] = array($error[0], $error[1]);
				}
			}
		}
		return $this->errors;
	}
	
	/**
	 * Outputs errors for all fields, with an optional prefix and suffix so you can wrap them in HTML elements.
	 * Note that if a field has multiple errors, only one of them will be displayed (the last one in the validation array).
	 *
	 * @param string $prefix string that will be inserted before each error message (e.g. <li>)
	 * @param string $suffix string that will be inserted after each error message (e.g. </li>)
	 * @return void
	 */
	public function display_errors($prefix = '', $suffix = '')
	{	
		$nested_errors = $this->get_errors();
		foreach ($nested_errors as $field_name => $field_error)
		{
			echo $prefix;
			echo $this->$field_name->label(false) . ': ' . $field_error[1];
			echo $suffix;
		}
	}

	/**
	 * Returns true if all fields' data pass validation tests.
	 *
	 * @param boolean $reprocess if true (default: false), call all validators again
	 * @return boolean
	 */
	public function is_valid($reprocess=FALSE)
	{
		if( $reprocess || is_null($this->valid) )
		{
			if( $this->bound )
			{
				$this->valid = TRUE;
				foreach( $this->fields as $name => &$field )
				{
					if( !$field->is_valid($reprocess) )
					{
						$this->valid = FALSE;
					}
				}

				// Set up the errors array.
				$this->get_errors();
			}
		}

		return $this->valid;
	}

	/**
	 * Returns the form's opening HTML tag.
	 *
	 * @param string $target the form target ($_SERVER['PHP_SELF'] by default)
	 * @return string the form's opening tag
	 */
	public function open($target=NULL, $attributes=NULL)
	{
		if( is_null($target) )
		{
			$target = $_SERVER['PHP_SELF'];
		}

		return sprintf('<form method="%s" action="%s"%s id="%s">',
			$this->method,
			htmlentities((string) $target),
			($this->multi_part) ? ' enctype="multipart/form-data"' : '',
			strtolower(get_class($this))
		)."\n";
	}

	/**
	 * Returns the form's closing HTML tag.
	 *
	 * @return string the form's closing tag
	 */
	public function close()
	{
		return "</form>\n";
	}

	/**
	 * Returns the buttons for submitting or resetting the form.
	 *
	 * @return string the form's closing tag
	 */
	public function buttons($buttons = array())
	{
		global $phorms_tr;

		if( empty($buttons) || !is_array($buttons) )
		{
			$reset = new Phorm_Widget_Reset();
			$submit = new Phorm_Widget_Submit();
			return $reset->html($phorms_tr['buttons_reset'], array( 'class' => 'phorms-reset' )).
				"\n".$submit->html($phorms_tr['buttons_validate'], array( 'class' => 'phorms-submit' ));
		}
		else
		{
			$out = array();
			foreach( $buttons as $button )
			{
				$out[] = $button[1]->html($button[0]);
			}
			return implode("\n", $out);
		}
	}

	/**
	 * Returns a string of all of the form's fields' HTML tags as a table.
	 *
	 * @return string the HTML form
	 * @see Phorm::as_table()
	 */
	public function __toString()
	{
		return $this->as_labels();
	}

	/**
	 * Returns the form fields.
	 *
	 * @author Thomas Lété
	 * @return string the HTML form
	 */
	public function as_labels()
	{
		$elts = array();
		foreach( $this->fields as $name => $field )
		{

			$label = $field->label();
			if(!empty($label))
			{
				$elts[] = '<div class="phorm_element">';
				$elts[] = $label;
				$elts[] = $field;
				$elts[] = '</div>';
			}
			else
			{
				$elts[] = strval($field);
			}
		}
		return implode("\n", $elts);
	}

	public function as_table($alt=FALSE, $template='')
	{
		if(empty($template))
		{
			$template[] = '<tr class="phorm_table_row%odd%">';
			$template[] = '<td class="phorm_table_cell_label">%label%</td>';
			$template[] = '<td class="phorm_table_cell_field">%field%%errors%</td>';
			$template[] = '<td class="phorm_table_help_text">%help_text%</td>';
			$template[] = '</tr>';
			$template = implode("\n", $template);
		}

		$out[] = '<table class="phorm_table">';
		$out[] = '<tbody>';
		$count = 0;
		foreach( $this->fields as $name => $field )
		{
			$odd = '';
			if($alt)
			{
				$odd = ($count%2) ? '' : ' phorm_odd_row';
				$count++;
			}
			$out[] = str_replace(
				array('%odd%','%label%','%field%','%errors%','%help_text%'),
				array($odd, $field->label(FALSE), $field->html(), $field->errors(), $field->help_text()),
				$template
			);
		}
		$out[] = '</tbody>';
		$out[] = '</table>';
		return implode("\n", $out);
	}

	/**
	 * Print the form completely.
	 *
	 * @author Thomas Lété
	 * @param string $target the form target ($_SERVER['PHP_SELF'] by default)
	 * @param bool $js include or not the javascript tag for live validation.
	 * @return null
	 */
	public function display($target = NULL, $js = TRUE)
	{
		echo $this->open($target).$this.$this->buttons().$this->close($js);
	}

}