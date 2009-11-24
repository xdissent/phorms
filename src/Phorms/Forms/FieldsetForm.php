<?php
/**
 * Fieldset Form
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License that is available
 * through the world-wide-web at the following URI: 
 * http://www.opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to obtain it 
 * through the web, please send a note to the author and a copy will be provided
 * for you.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Forms
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */

/**
 * Fieldset Form
 * 
 * The abstract Phorms_Forms_FieldsetForm class is a subclass of Phorm. It 
 * additionally specifies one abstract method: 'defineFieldsets', which must set
 * the 'fieldsets' attribute. It should be an array of Fieldset instances to
 * use in the form.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Forms
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
abstract class Phorms_Forms_FieldsetForm extends Phorms_Forms_Form
{
    /**
     * The Fieldset Form's constructor.
     *
     * @param mixed   $method     Whether to use GET or POST for the form data.
     * @param boolean $multi_part True if this form accepts files.
     * @param array   $data       Initial/default data for form fields (e.g. 
     *                            array('first_name'=>'enter your name')).
     *
     * @return void
     */
    public function __construct($method=Phorms_Forms_Form::GET, 
    boolean $multi_part=false, array $data=array()) {
        parent::__construct($method, $multi_part, $data);
        $this->defineFieldsets();
    }
    
    /**
     * Abstract method that sets the Form's fieldsets as class attributes. 
     *
     * @return void
     */
    abstract protected function defineFieldsets();

    /**
     * Returns the form fields as a series of HTML table rows. Does not include
     * the table's opening and closing tags, nor the table's tbody tags.
     *
     * @return string the HTML form
     */
    public function asTable()
    {
        $elts = array();
        foreach ($this->fieldsets as $fieldset) {
            $elts[] = sprintf(
                '<tr><td colspan="2"><fieldset><legend>%s</legend><table>', 
                $fieldset->label
            );
            
            foreach ($fieldset->field_names as $field_name) {
                $field = $this->$field_name;
                $label = $field->label();

                if ($label !== '') {
                
                    $elts[] = sprintf(
                        '<tr><th>%s:</th><td>%s</td></tr>', 
                        $label, 
                        $field
                    );
                    
                } else {
                
                    $elts[] = strval($field);
                }
            }
            $elts[] = '</table></fieldset></td></tr>';
        }
        return implode($elts, "\n");
    }
}

?>