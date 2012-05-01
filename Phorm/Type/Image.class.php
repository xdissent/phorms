<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Types
 */
/**
 * Phorm_Type_Image
 *
 * Adds a few additional properties specific for images to the Phorm_Type_File class.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Types
 * @see Phorm_Type_File
 */
class Phorm_Type_Image extends Phorm_Type_File
{

	/**
	 * The image's width in pixels.
	 */
	public $width;
	/**
	 * The image's height in pixels.
	 */
	public $height;
	/**
	 * The image's type constant.
	 */
	public $type;

	public function __construct($file_data)
	{
		parent::__construct($file_data);
		list($this->width, $this->height, $this->type) = getimagesize($this->tmp_name);
	}

}