<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Integer
 *
 * A field that accepts only integers.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Integer extends Phorm_Field
{

	/**
	 * Stores the max number of digits permitted.
	 */
	private $max_digits;

	/**
	 * @param string $label the field's text label
	 * @param int $max_digits the maximum number of digits permitted
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $size, $max_digits, array $validators=array(), array $attributes=array())
	{
		$this->max_digits = $max_digits;
		$attributes['maxlength'] = $max_digits;
		$attributes['size'] = $size;
		parent::__construct($label, $validators, $attributes);
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
	 * Validates that the value is parsable as an integer and that it is fewer
	 * than $this->max_digits digits.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		if( !filter_var($value,FILTER_VALIDATE_INT) )
		{
			throw new Phorm_ValidationError('field_invalid_integer');
		}

		if( strlen((string) $value) > $this->max_digits )
		{
			throw new Phorm_ValidationError(serialize(array('field_invalid_integer_sizelimit', $this->max_digits)));
		}
	}

	/**
	 * Parses the value as an integer.
	 *
	 * @param string $value
	 * @return int
	 */
	public function import_value($value)
	{
		return (int) (html_entity_decode((string) $value));
	}

}