<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_Checkbox
 *
 * A checkbox field.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_Checkbox extends Phorm_Widget
{

	/**
	 * Stores whether or not the field is checked.
	 */
	private $checked = FALSE;

	/**
	 * @param boolean $checked whether the field is initially checked
	 * @return null
	 */
	public function __construct($checked=FALSE)
	{
		$this->checked = $checked;
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
		$attributes['type'] = 'checkbox';
		if( $this->checked )
		{
			$attributes['checked'] = 'checked';
		}
		return parent::serialize($value, $attributes);
	}

}