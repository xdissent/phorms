<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_DecimalField
 *
 * A field that accepts only decimals of a specified precision.
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Decimal extends Phorm_Field
{

	/**
	 * The maximum precision of the field's value.
	 */
	private $precision;

	/**
	 * @param string $label the field's text label
	 * @param int $precision the maximum number of decimals permitted
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $size, $precision, array $validators=array(), array $attributes=array())
	{
		$attributes['size'] = $size;
		parent::__construct($label, $validators, $attributes);
		$this->precision = $precision;
	}

	/**
	 * Returns a new CharWidget.
	 *
	 * @return CharWidget
	 */
	public function get_widget()
	{
		return new Phorm_Widget_Text();
	}

	/**
	 * Validates that the value is parsable as a float.
	 *
	 * @param string value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		if( !filter_var($value,FILTER_VALIDATE_FLOAT ) )
		{
			throw new Phorm_ValidationError('field_invalid_decimal');
		}
	}

	/**
	 * Returns the parsed float, rounded to $this->precision digits.
	 *
	 * @param string $value
	 * @return float the parsed value
	 */
	public function import_value($value)
	{
		return round((float) (html_entity_decode($value)), $this->precision);
	}

}