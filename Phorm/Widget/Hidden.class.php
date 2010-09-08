<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_Hidden
 *
 * A hidden text field.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_Hidden extends Phorm_Widget
{

	/**
	 * Returns the field as serialized HTML.
	 *
	 * @param mixed $value the form widget's value attribute
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		$attributes['type'] = 'hidden';
		return parent::serialize($value, $attributes);
	}

}