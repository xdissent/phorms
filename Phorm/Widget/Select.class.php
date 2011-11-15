<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_Select
 *
 * A select widget (drop-down list.)
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_Select extends Phorm_Widget
{

	/**
	 * The choices for this field as an array of actual=>display values.
	 */
	private $choices;

	/**
	 * @param array $choices the choices as an array of actual=>display values
	 * @return null
	 */
	public function __construct(array $choices)
	{
		$this->choices = $choices;
	}

	/**
	 * Returns the field as serialized HTML.
	 *
	 * @param mixed $value the form widget's selected value
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		$options = array();
		foreach( $this->choices as $actual => $display )
		{
			$option_attributes = array( 'value' => $this->clean_string($actual) );
			if( $actual == $value )
			{
				$option_attributes['selected'] = 'selected';
			}
			$options[] = sprintf("<option %s>%s</option>\n", $this->serialize_attributes($option_attributes), $this->clean_string($display));
		}

		return sprintf('<select %s>%s</select>', $this->serialize_attributes($attributes), implode($options));
	}

}