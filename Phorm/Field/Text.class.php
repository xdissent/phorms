<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Text
 *
 * A simple text field.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Text extends Phorm_Field
{

	/**
	 * Stores the maximum value length in characters.
	 * @var int
	 */
	private $max_length;

	/**
	 * @param string $label the field's text label
	 * @param int $size the field's size attribute
	 * @param int $max_length the maximum size in characters
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $size, $max_length, array $validators=array(), array $attributes=array())
	{
		$this->max_length = $max_length;
		$attributes['maxlength'] = $max_length;
		$attributes['size'] = $size;
		parent::__construct($label, $validators, $attributes);
	}

	/**
	 * Returns a new CharWidget.
	 *
	 * @return CharWidget
	 */
	protected function get_widget()
	{
		return new Phorm_Widget_Text();
	}

	/**
	 * Validates that the value is less than $this->max_length;
	 *
	 * @return null
	 * @throws Phorm_ValidationError
	 * @see Phorm_Field_Text::$max_width
	 */
	public function validate($value)
	{
		if( strlen($value) > $this->max_length )
		{
			throw new Phorm_ValidationError(serialize(array( 'field_invalid_text_sizelimit', $this->max_length)));
		}
		return $value;
	}

	/**
	 * Imports the value by decoding HTML entities.
	 *
	 * @param string $value
	 * @return string the decoded value
	 */
	public function import_value($value)
	{
		return html_entity_decode((string) $value);
	}

}