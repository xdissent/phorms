<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Types
 */
/**
 * Phorm_Type_File
 *
 * Record class for Phorm_Field_File data.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Types
 * @see Phorm_Field_File
 */
class Phorm_Type_File
{

	/**
	 * The name of the file.
	 */
	public $name;
	/**
	 * The mime type of the file.
	 */
	public $type;
	/**
	 * The path of the temporary file.
	 */
	public $tmp_name;
	/**
	 * An error message, if there was an error uploading the file.
	 */
	public $error;
	/**
	 * The size of the file in bytes.
	 */
	public $bytes;

	/**
	 * @param array $file_data the uploaded file's array data from $_FILES
	 * @return null
	 */
	public function __construct(array $file_data)
	{
		$this->name = $file_data['name'];
		$this->type = $file_data['type'];
		$this->tmp_name = $file_data['tmp_name'];
		$this->error = $file_data['error'];
		$this->bytes = $file_data['size'];
	}

	/**
	 * Moves the files from the temporary directory to another location. The new
	 * file will have the original file's name.
	 *
	 * @return boolean true on success, false on error
	 * @see File::$tmp_name,File::$name
	 */
	public function move_to($path)
	{
		$new_name = sprintf('%s/%s', $path, $this->name);
		move_uploaded_file($this->tmp_name, $new_name);
		return $new_name;
	}

}