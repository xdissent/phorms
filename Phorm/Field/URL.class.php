<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_URL
 *
 * A text field that only accepts a reasonably-formatted URL. Supports HTTP(S)
 * and FTP. If a value is missing the HTTP(S)/FTP prefix, adds it to the final
 * value.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_URL extends Phorm_Field_Text
{

	/**
	 * Prepares the value by inserting http:// to the beginning if missing.
	 *
	 * @param string $value
	 * @return string
	 */
	public function prepare_value($value)
	{
		if( !empty($value) && !filter_var($value,FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) )
		{
			return 'http://'.$value;
		}

		return filter_var($value, FILTER_SANITIZE_URL);
	}

	/**
	 * Validates the the value is a valid URL (mostly).
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		$value = parent::validate($value);
		if( !filter_var($value,FILTER_VALIDATE_URL,FILTER_FLAG_SCHEME_REQUIRED) )
		{
			throw new Phorm_ValidationError('field_invalid_url');
		}
	}

}