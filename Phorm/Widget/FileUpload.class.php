<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_FileUpload
 *
 * A file upload field. Requires that the form have enctype="multipart/form-data"
 * set to function.
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_FileUpload extends Phorm_Widget
{

	/**
	 * Stores an array of valid mime types.
	 */
	private $types;

	/**
	 * @param array $valid_mime_types e.g. array("image/jpeg", "image/jpg", "image/png", "image/gif")
	 * @return null
	 */
	public function __construct(array $valid_mime_types)
	{
		$this->types = $valid_mime_types;
	}

	/**
	 * Returns the field as serialized HTML.
	 *
	 * @param mixed $value the form widget's value attribute
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		$attributes['type'] = 'file';
		$attributes['accept'] = implode(',', $this->types);
		return parent::serialize($value, $attributes);
	}

}