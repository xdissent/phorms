<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Regex
 *
 * A text field that validates using a regular expression and imports to an
 * array of captured values.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Regex extends Phorm_Field_Text
{

	/**
	 * The (pcre) regular expression.
	 */
	private $regex;
	/**
	 * The error message thrown when unmatched.
	 */
	private $message;
	/**
	 * Storage for matches during validation so that the expression needn't run twice.
	 */
	private $matches;

	/**
	 *
	 * @param string $label the field's text label
	 * @param string $regex the (pcre) regex used to validate and parse the field
	 * @param string $error_msg the message thrown on a regex mismatch
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $regex, $error_msg, array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, 25, 100, $validators, $attributes);
		$this->regex = $regex;
		$this->message = $error_msg;
	}

	/**
	 * Validates that the value matches the regular expression.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		parent::validate($value);
		if( !filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $this->regex) ) ) )
		{
			throw new Phorm_ValidationError($this->message);
		}
	}

	/**
	 * Returns the captured values that were parsed inside validate().
	 *
	 * @param string $value
	 * @return array the captured matches
	 */
	public function import_value($value)
	{
		return $this->matches;
	}

}