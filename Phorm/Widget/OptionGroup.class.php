<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_OptionGroup
 *
 * A compound widget made up of multiple CheckboxWidgets or RadioWidgets.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_OptionGroup extends Phorm_Widget
{

	/**
	 * The options for this field as an array of actual=>display values.
	 */
	private $options;
	/**
	 * The options for this field as an array of actual=>display values.
	 */
	private $widget;

	/**
	 * @param array $options the options as an array of actual=>display values
	 * @return null
	 */
	public function __construct(array $options, $widget='Phorm_Widget_Checkbox')
	{
		$this->options = $options;
		$this->widget = $widget;
	}

	/**
	 * @param array $value an array of the field's values
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 */
	public function html($value, array $attributes=array())
	{
		if( is_null($value) )
			$value = array();

		foreach( $attributes as $key => $val )
		{
			$attributes[htmlentities((string) $key)] = htmlentities((string) $val);
		}

		return $this->serialize($value, $attributes);
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
		$html = "";
		foreach( $this->options as $actual => $display )
		{
			$option = new $this->widget(in_array($actual, $value));
			$html .= sprintf("<label>%s %s</label>\n", $option->html($actual, $attributes), htmlentities($display));
		}

		return $html;
	}

}