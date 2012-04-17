<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_FileUpload
 *
 * A field representing a file upload input.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 * @uses Phorm_Type_File
 */
class Phorm_Field_FileUpload extends Phorm_Field
{

	/**
	 * Stores the valid types for this field.
	 */
	private $types;
	/**
	 * Stores the maximum size boundary in bytes.
	 * @var int
	 */
	private $max_size;

	/**
	 * @param string $label the field's string label
	 * @param array $mime_types a list of valid mime types
	 * @param int $max_size the maximum upload size in bytes
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, array $mime_types, $max_size, array $validators=array(), array $attributes=array())
	{
		$this->types = $mime_types;
		$this->max_size = $max_size;
		parent::__construct($label, $validators, $attributes);
	}

	/**
	 * Returns true if the file was uploaded without an error.
	 *
	 * @return boolean
	 */
	protected function file_was_uploaded()
	{
		$file = $this->get_file_data();
		return !empty($file['tmp_name']) && !$file['error'];
	}

	/**
	 * Returns an error message for a file upload error code.
	 *
	 * @param int $errno the error code (from $_FILES['name']['error'])
	 * @return string the error message
	 */
	protected function file_upload_error($errno)
	{
		global $phorms_tr;
		switch( $errno )
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'field_file_toolarge';

			case UPLOAD_ERR_PARTIAL:
				return 'field_file_uploaderror';

			case UPLOAD_ERR_NO_FILE:
				return 'field_file_notsent';

			case UPLOAD_ERR_NO_TMP_DIR:
			case UPLOAD_ERR_CANT_WRITE:
			case UPLOAD_ERR_EXTENSION:
				return serialize(array('field_file_syserror', $errno));

			case UPLOAD_ERR_OK:
			default:
				return false;
		}
	}

	/**
	 * Returns a FileWidget.
	 *
	 * @return FileWidget
	 * @see FileWidget,FileField::$types
	 */
	protected function get_widget()
	{
		return new Phorm_Widget_FileUpload($this->types);
	}

	/**
	 * Returns an array of file upload data.
	 *
	 * @return array file upload data
	 */
	protected function get_file_data()
	{
		$data = $_FILES[$this->get_attribute('name')];
		$data['error'] = $this->file_upload_error($data['error']);
		return $data;
	}

	/**
	 * Returns a new File instance for this field's data.
	 *
	 * @return File a new File instance
	 * @see File
	 */
	protected function get_file()
	{
		return new Phorm_File($this->get_file_data());
	}

	/**
	 * On a successful upload, returns a new File instance.
	 *
	 * @param array $value the file data from $_FILES
	 * @return File a new File instance
	 * @see File
	 */
	public function import_value($value)
	{
		if( $this->file_was_uploaded() )
		{
			return $this->get_file();
		}
	}

	/**
	 * Returns the file's $_FILES data array or false if the file was not uploaded.
	 *
	 * @param mixed $value
	 * @return boolean|File
	 */
	public function prepare_value($value)
	{
		if( $this->file_was_uploaded() )
		{
			return $this->get_file();
		}
		return false;
	}

	/**
	 * Throws a ValidationError if the file upload resulted in an error, if
	 * the file was not a valid type, or if the file exceded the maximum size.
	 *
	 * @param mixed $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		$file = $this->get_file_data();

		if( $file['error'] )
		{
			throw new Phorm_ValidationError($file['error']);
		}

		if( is_array($this->types) && !in_array($file['type'], $this->types) )
		{
			throw new Phorm_ValidationError(serialize(array('field_file_badtype', $file['type'])));
		}

		if( $file['size'] > $this->max_size )
		{
			throw new Phorm_ValidationError(serialize(array( 'field_file_sizelimit', number_format($this->max_size))) );
		}
	}

}