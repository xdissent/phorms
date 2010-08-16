<?php

abstract class PhormExt extends Phorm
{

	public function __construct($method=Phorm::GET, $multi_part=false, $data=array( ))
	{
		parent::__construct($method, $multi_part, $data);
	}

	/**
	 * Returns a string of all of the form's fields' HTML tags as labels only.
	 * @return string the HTML form
	 * @author Thomas Lété
	 * @see PhormExt::as_labels()
	 * */
	public function __toString()
	{
		return $this->as_labels();
	}

	/**
	 * Returns the form fields as a series of paragraphs.
	 * @return string the HTML form
	 * @author Thomas Lété
	 * */
	public function as_labels()
	{
		$elts = array( );
		foreach( $this->getFields() as $name => $field )
		{
			$label = $field->label();
			if( $label !== '' )
			{
				$elts[] = sprintf("<p>\n\t%s\n\t%s\n</p>\n", str_replace('</', ' :</', $field->label()), $field);
			}
			else
			{
				$elts[] = strval($field);
			}
		}
		return implode($elts);
	}

	public function buttons()
	{
		return '<p>
					<input type="submit" class="phorms-submit" value="Valider" />
					<input type="reset" class="phorms-reset" value="R&eacute;initialiser" />
				</p>
		';
	}

}

abstract class FieldsetPhormExt extends FieldsetPhorm
{

	public function __construct($method=Phorm::GET, $multi_part=false, $data=array( ))
	{
		parent::__construct($method, $multi_part, $data);
		$this->define_fieldsets();
	}

	/**
	 * Returns a string of all of the form's fields' HTML tags as labels only.
	 * @return string the HTML form
	 * @author Thomas Lété
	 * @see FieldsetPhormExt::as_labels()
	 * */
	public function __toString()
	{
		return $this->as_labels();
	}

	/**
	 * Returns the form fields as a series of paragraphs.
	 * @return string the HTML form
	 * @author Thomas Lété
	 * */
	public function as_labels()
	{
		$elts = array( );
		foreach( $this->fieldsets as $fieldset )
		{
			$elts[] = sprintf("<fieldset>\n\t<legend>%s</legend>\n", $fieldset->label);
			foreach( $fieldset->field_names as $field_name )
			{
				$field = $this->$field_name;
				$label = $field->label();

				if( $label !== '' )
				{
					$elts[] = sprintf("<p>\n\t%s\n\t%s\n</p>\n", str_replace('</', ' :</', $label), $field);
				}
				else
				{
					$elts[] = strval($field);
				}
			}
			$elts[] = '</fieldset>';
		}
		return implode($elts, "\n");
	}

	public function buttons()
	{
		return '<p>
					<input type="submit" class="phorms-submit" value="Valider" />
					<input type="reset" class="phorms-reset" value="R&eacute;initialiser" />
				</p>
		';
	}

}

?>
