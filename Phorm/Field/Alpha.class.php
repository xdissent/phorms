<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Alpha
 *
 * A text field that only accepts alpha (a-z) characters.
 *
 * @author Lété Thomas
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Alpha extends Phorm_Field_Text
{

	/**
	 * Validates that the value is alpha only.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		$value = parent::validate($value);
		if( preg_match('/[0-9\s]*/iu', $value) )
		{
			throw new Phorm_ValidationError('field_invalid_alpha');
		}
	}

}