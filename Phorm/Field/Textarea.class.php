<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Textarea
 *
 * A large text field using a textarea tag.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Textarea extends Phorm_Field
{

	/**
	 * @param string $label the field's text label
	 * @param int $rows the number of rows
	 * @param int $cols the number of columns
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $rows, $cols, array $validators=array(), array $attributes=array())
	{
		$attributes['cols'] = $cols;
		$attributes['rows'] = $rows;
		parent::__construct($label, $validators, $attributes);
	}

	/**
	 * Returns a new TextWidget.
	 *
	 * @return TextWidget
	 */
	protected function get_widget()
	{
		return new Phorm_Widget_Textarea();
	}

	/**
	 * Returns true.
	 *
	 * @return boolean
	 */
	public function validate($value)
	{
		return TRUE;
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