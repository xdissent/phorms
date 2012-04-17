<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Widgets
 */
/**
 * Phorm_Widget_Cancel
 *
 * A reset button field.
 *
 * @author Thomas Lété
 * @package Phorms
 * @subpackage Widgets
 */
class Phorm_Widget_Cancel extends Phorm_Widget
{

	/**
	 * The "go back" url.
	 * @var string
	 */
	private $url;

	/**
	 * @param string $url to go on click.
	 * @return null
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * Returns the button as serialized HTML.
	 *
	 * @param mixed $value the form widget's value attribute
	 * @param array $attributes key=>value pairs corresponding to HTML attributes' name=>value
	 * @return string the serialized HTML
	 */
	protected function serialize($value, array $attributes=array())
	{
		$attributes['type'] = 'button';
		$attributes['onclick'] = 'window.location.href=\''.str_replace("'", "\'", $this->url).'\'';
		return parent::serialize($value, $attributes);
	}

}