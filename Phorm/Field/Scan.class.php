<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Scan
 *
 * Akin to the RegexField, but instead using sscanf() for more rigid matching
 * and type-cast values.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Scan extends Phorm_Field_Text
{

	/**
	 * The sscanf() format.
	 */
	private $format;
	/**
	 * The error message on match failure.
	 */
	private $message;
	/**
	 * Storage for the matched values to prevent calling sscanf twice.
	 */
	private $matched;

	/**
	 *
	 * @param string $label the field's text label
	 * @param string $format the sscanf format used to validate and parse the field
	 * @param string $error_msg the message thrown on a mismatch
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $format, $error_msg, array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, 25, 100, $validators, $attributes);
		$this->format = $format;
		$this->message = $error_msg;
	}

	/**
	 * Validates that the value matches the sscanf format.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		parent::validate($value);
		$this->matched = sscanf($value, $this->format);
		if( empty($this->matched) )
		{
			throw new Phorm_ValidationError($this->message);
		}
	}

	/**
	 * Returns the parsed matches that were captured in validate().
	 *
	 * @param string $value
	 * @return array the captured values
	 */
	public function import_value($value)
	{
		return $this->matched;
	}

}