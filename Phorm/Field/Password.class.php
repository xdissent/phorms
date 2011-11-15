<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Password
 *
 * A password field that uses a user-specified hash function to import values.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Password extends Phorm_Field_Text
{

	/**
	 * The hash function to encode the user-submitted value.
	 */
	private $hash_function;

	/**
	 *
	 * @param string $label the field's text label
	 * @param int $size the field's size attribute
	 * @param int $max_length the maximum size in characters
	 * @param callback $hash_function a (string) function or array (instance, string method) callback
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $size, $max_length, $hash_function, array $validators=array(), array $attributes=array())
	{
		$this->hash_function = $hash_function;
		parent::__construct($label, $size, $max_length, $validators, $attributes);
	}

	/**
	 * Returns a PasswordWidget.
	 *
	 * @return PasswordWidget
	 */
	public function get_widget()
	{
		return new Phorm_Widget_Password();
	}

	/**
	 * Returns a hash-encoded value.
	 *
	 * @param string $value
	 * @return string the encoded value
	 */
	public function import_value($value)
	{
		return call_user_func($this->hash_function, $value);
	}

}