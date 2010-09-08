<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_ImageUpload
 *
 * A Phorm_Field_FileUpload that is pre-configured for images. Valid types are png, gif, jpeg/jpg.
 * Returns a Phorm_Type_Image instance instead of a Phorm_Type_File instance. Identical to the
 * Phorm_Field_FileUpload in all other ways.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 * @uses Phorm_Field_FileUpload, Phorm_Type_Image
 */
class Phorm_Field_ImageUpload extends Phorm_Field_FileUpload
{

	/**
	 * @param string $label the field's string label
	 * @param int $max_size the maximum upload size in bytes
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 * @param array $alllowed a list of allowed extensions, aside from the default (gif,png,jpeg,jpg).
	 */
	public function __construct($label, $max_size, array $validators=array(), array $attributes=array(), array $allowed=array())
	{
		parent::__construct($label, array_merge($allowed, array( 'image/png', 'image/gif', 'image/jpg', 'image/jpeg' )), $max_size, $validators, $attributes);
	}

	/**
	 * Returns a new Image.
	 *
	 * @return Phorm_Type_Image
	 */
	protected function get_file()
	{
		return new Phorm_Type_Image($this->get_file_data());
	}

}