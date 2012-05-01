<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 */
/**
 * Phorm_Fieldset
 *
 * Fieldset class that is used to compose a Phorm instance.
 *
 * @author Greg Thornton <xdissent@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @see Phorm_Phorm,Phorm_Field
 */
class Phorm_Fieldset
{

	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $label;
	/**
	 * @var array
	 */
	public $field_names = array( );

	/**
	 * @param string $name Name of the fieldset
	 * @param string $label Text for the legend tag inside the fieldset
	 * @param array $field_names Holds the names of the fields in the fieldset
	 */
	function __construct($name, $label, $field_names=array( ))
	{
		$this->id = 'id_'.$name;
		$this->name = $name;
		$this->label = $label;
		$this->field_names = $field_names;
	}

}