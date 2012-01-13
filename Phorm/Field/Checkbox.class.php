<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Checkbox
 *
 * A field representing a boolean choice using a checkbox field.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Checkbox extends Phorm_Field
{

	/**
	 * True when the field is checked (true).
	 * @var boolean
	 */
	private $checked;
        
	/**
	 * True when the user data field is checked.
	 * @var boolean
	 */
	private $user_checked;

	/**
	 * @param string $label the field's text label
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, $validators, $attributes);
		parent::set_value('on');
		$this->checked = false;
		$this->user_checked = false;
	}

	/**
	 * Sets the value of the field.
	 *
	 * @param boolean $value
	 * @return null
	 */
	public function set_value($value)
	{
		$this->checked = (boolean) $value;
		$this->user_checked = ($value === 'on');     
	}

	/**
	 * Returns true if the field is checked.
	 *
	 * @return boolean
	 */
	public function get_value()
	{
		return $this->checked;
	}

	/**
	 * Returns a new CheckboxWidget.
	 *
	 * @return CheckboxWidget
	 */
	public function get_widget()
	{
		return new Phorm_Widget_Checkbox($this->checked);
	}

	/**
	 * Returns null.
	 *
	 * @return null
	 */
	public function validate($value)
	{
		return NULL;
	}

	/**
	 * Validates that the checkbox is checked.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate_required_field($value)
	{
		if (!$this->checked)
		{
			throw new Phorm_ValidationError('validation_required');
		}
	}

	/**
	 * Returns true if the field was checked in the user-submitted data, false
	 * otherwise.
	 *
	 * @return boolean
	 */
	public function import_value($value)
	{
		$this->checked = $this->user_checked;
		return $this->checked;
	}

	/**
	 * Returns the value.
	 *
	 * @param string $value
	 * @param string
	 */
	public function prepare_value($value)
	{
		return $value;
	}

}