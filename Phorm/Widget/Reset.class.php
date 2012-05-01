<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_Reset
 *
 * A reset button field.
 *
 * @author Thomas Lété
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_Reset extends Phorm_Widget
{

	/**
	 * Returns the button as serialized HTML.
	 * @author Thomas Lété
	 * @param mixed $value the form widget's value attribute
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		$attributes['type'] = 'reset';
		return parent::serialize($value, $attributes);
	}

}