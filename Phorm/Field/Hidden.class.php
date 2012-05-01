<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_Hidden
 *
 * A hidden text field that does not print a label.
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_Hidden extends Phorm_Field_Text
{

	/**
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct(array $validators=array(), array $attributes=array())
	{
		parent::__construct('', 25, 255, $validators, $attributes);
	}

	/**
	 * Does not print out a label.
	 *
	 * @return string an empty string
	 */
	public function label()
	{
		return '';
	}

	/**
	 * Does not print out the help text.
	 *
	 * @return string an empty string.
	 */
	public function help_text()
	{
		return '';
	}

	/**
	 * Returns a new HiddenWidget.
	 *
	 * @return HiddenWidget
	 */
	protected function get_widget()
	{
		return new Phorm_Widget_Hidden();
	}

}