<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Email
 *
 * A text field that only accepts a valid email address.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Email extends Phorm_Field_Text
{

	/**
	 * Validates that the value is a valid email address.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		parent::validate($value);
		if( !filter_var($value, FILTER_VALIDATE_EMAIL) )
		{
			throw new Phorm_ValidationError('field_invalid_email');
		}
	}

}