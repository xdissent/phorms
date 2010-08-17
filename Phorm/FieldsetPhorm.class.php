<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 */
/**
 * FieldsetPhorm
 *
 * The abstract FieldsetPhorm class is a subclass of Phorm. It additionally
 * specifies one abstract method: 'define_fieldsets', which must set the
 * 'fieldsets' attribute. It should be an array of Fieldset instances to
 * use in the form.
 *
 * @author Greg Thornton <xdissent@gmail.com>
 * @package Phorms
 * @see Phorm_Fieldset
 * @example ../examples/comment_form.php A simple comment form
 */
abstract class Phorm_FieldsetPhorm extends Phorm_Phorm
{

	public function __construct($method='get', $multi_part=false, $data=array( ))
	{
		parent::__construct($method, $multi_part, $data);
		$this->define_fieldsets();
	}

	/**
	 * Returns the form fields.
	 *
	 * @author Thomas Lété
	 * @return string the HTML form
	 */
	public function as_labels()
	{
		$elts = array( );

		foreach( $this->fieldsets as $fieldset )
		{
			$elts[] = '<fieldset>';
			$elts[] = '<legend>'.$fieldset->label.'</legend>';

			foreach( $fieldset->field_names as $field_name )
			{
				if( !empty($field->label) )
				{
					$elts[] = $field->label;
					$elts[] = $this->$field_name;
				}
				else
				{
					$elts[] = strval($this->$field_name);
				}
			}

			$elts[] = '</fieldset>';
		}

		return implode("\n", $elts);
	}

}