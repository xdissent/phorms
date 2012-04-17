<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_MultipleChoice
 *
 * A compound field offering multiple choices as a select multiple tag.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_MultipleChoice extends Phorm_Field
{

	/**
	 * Specifies that this field's name attribute must be post-fixed by [].
	 * @var boolean
	 */
	public $multi_field = TRUE;
	/**
	 * Stores the field options as actual_value=>display_value.
	 * @var array
	 */
	private $choices;
	/**
	 * Which widget should we use for displaying the choices?
	 * Defauls to select multiple tag, can also be a field of radio or check
	 * boxes (yes, radio is select-one; just think multiple-choice like a
	 * school exam).
	 * @var Widget
	 */
	private $widget;

	/**
	 * @param string $label the field's text label
	 * @param array $choices a list of choices as actual_value=>display_value
	 * @param array $widget is one of MultiSelectWidget (default), RadioWidget, or CheckboxWidget
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, array $choices, $widget='Phorm_Widget_SelectMultiple', array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, $validators, $attributes);
		$this->choices = $choices;
		$this->widget = $widget;
	}

	/**
	 * Returns a new instance of the widget specified in the constructor
	 *
	 * @author Aaron Stone <aaron@serendipity.cx>
	 * @return a Widget
	 * @throws Exception
	 */
	public function get_widget()
	{
		switch( $this->widget )
		{
			case 'Phorm_Widget_SelectMultiple':
				return new Phorm_Widget_SelectMultiple($this->choices);
			case 'Phorm_Widget_Radio':
			case 'Phorm_Widget_Checkbox':
				return new Phorm_Widget_OptionGroup($this->choices, $this->widget);
			default:
				throw new Exception('Invalid widget: '.(string) $this->widget);
		}
	}

	/**
	 * Validates that each of the selected choice exists in $this->choices.
	 *
	 * @param array $value
	 * @return null
	 * @throws Phorm_ValidationError
	 * @see Phorm_Field_MultipleChoice::$choices
	 */
	public function validate($value)
	{

		if( !is_array($value) )
		{
			throw new Phorm_ValidationError('field_invalid_multiplechoice_badformat');
		}

		foreach( $value as $v )
		{
			if( !in_array($v, array_keys($this->choices)) )
			{
				throw new Phorm_ValidationError('field_invalid_multiplechoice_badformat');
			}
		}
	}

	/**
	 * Imports the value as an array of the actual values (from $this->choices.)
	 *
	 * @param array $value
	 * @return array
	 */
	public function import_value($value)
	{
		if( is_array($value) )
		{
			foreach( $value as $key => &$val )
			{
				$val = html_entity_decode($val);
			}
		}
		return $value;
	}

	/**
	 * Pre-processes an array of values for validation, handling magic quotes if used.
	 *
	 * @author Aaron Stone <aaron@serendipity.cx>
	 * @param array $value the value from the form array
	 * @return array the pre-processed value
	 */
	public function prepare_value($value)
	{
		if( is_array($value) && get_magic_quotes_gpc() )
		{
			foreach( $value as $key => &$val )
			{
				$val = stripslashes($val);
			}
		}
		return $value;
	}

}