<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_DateTime
 *
 * A text field that accepts a custom date/time format.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_DateTime extends Phorm_Field_Text
{

	/**
	 * @param string $label the field's text label
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, 25, 100, $validators, $attributes);
	}

	/**
	 * Validates that the value is parsable as a date/time value.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		parent::validate($value);

		if( !filter_var($value, FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>'/^([0-9]{2})[\-|\/]([0-9]{2})[\-|\/]([0-9]{4})$/'))) )
		{
			throw new Phorm_ValidationError('field_invalid_datetime_format');
		}

		if( !strptime(strstr($value, '-', '/'), '%d/%m/%Y') )
		{
			throw new Phorm_ValidationError('field_invalid_datetime_format');
		}
	}

	/**
	 * Imports the value and returns a unix timestamp (the number of seconds
	 * since the epoch.)
	 *
	 * @param string $value
	 * @return int the date/time as a unix timestamp
	 */
	public function import_value($value)
	{
		return strptime(strstr(parent::import_value($value), '-', '/'), '%d/%m/%Y');
	}

}