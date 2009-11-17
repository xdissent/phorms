<?php
abstract class PhormExt extends Phorm
{
    public function __construct($method=Phorm::GET, $multi_part=false, $data=array())
    {
        parent::__construct($method, $multi_part, $data);
    }
    /**
     * Returns a string of all of the form's fields' HTML tags as labels only.
     * @return string the HTML form
     * @author Thomas Lété
     * @see Phorm::as_labels()
     **/
    public function __toString()
    {
        return $this->as_labels();
    }
    
    /**
     * Returns the form fields as a series of paragraphs.
     * @return string the HTML form
     * @author Thomas Lété
     **/
    public function as_labels()
    {
        $elts = array();
        foreach ($this->getFields() as $name => $field)
        {
            $label = $field->label();
            if ($label !== '')
                $elts[] = sprintf('<p>%s%s</p>', str_replace('</', ' :</', $field->label()), $field); 
            else
                $elts[] = strval($field);
        }
        return implode($elts);
    }
}

abstract class FieldsetPhormExt extends Phorm
{
    public function __construct($method=Phorm::GET, $multi_part=false, $data=array())
    {
        parent::__construct($method, $multi_part, $data);
        $this->define_fieldsets();
    }
    /**
     * Returns the form fields as a series of paragraphs.
     * @return string the HTML form
     * @author Thomas Lété
     **/
    public function as_labels()
    {
        $elts = array();
        foreach ($this->fieldsets as $fieldset)
        {
            $elts[] = sprintf('<fieldset><legend>%s</legend>', $fieldset->label);
            foreach ($fieldset->field_names as $field_name) {
                $field = $this->$field_name;
                $label = $field->label();

                if ($label !== '')
                    $elts[] = sprintf('<p>%s%s</p>', str_replace('</', ' :</', $label), $field);
                else
                    $elts[] = strval($field);
            }
            $elts[] = '</fieldset>';
        }
        return implode($elts, "\n");
    }
}
?>
