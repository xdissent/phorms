<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * PhormWidget
 *
 * The base class of all HTML form widgets.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget
{

	/**
	 * Serializes an array of key=>value pairs as an HTML attributes string.
	 *
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize_attributes(array $attributes=array())
	{
		if(empty($attributes))
		{
			return '';
		}

		$attr = array();
		foreach( $attributes as $key => $val )
		{
			$attr[] = $key.'="'.$val.'"';
		}
		return ' '.implode(' ', $attr).' ';
	}

	/**
	 * Serializes the widget as an HTML form input.
	 *
	 * @param string $value the form widget's value
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		return '<input value="'.$value.'"'.$this->serialize_attributes($attributes).'/>';
	}

	/**
	 * Casts a value to a string and encodes it for HTML output.
	 *
	 * @param mixed $str
	 * @return a decoded string
	 */
	protected function clean_string($str)
	{
		return htmlentities((string) $str);
	}

	/**
	 * Returns the field as serialized HTML.
	 *
	 * @param mixed $value the form widget's value attribute
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	public function html($value, array $attributes=array())
	{
		$value = $this->clean_string($value);

		foreach( $attributes as $key => $val )
		{
			$attributes[$this->clean_string($key)] = $this->clean_string($val);
		}

		return $this->serialize($this->clean_string($value), $attributes);
	}

}