<?php
/**
 * Boolean Field
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
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
 
/**
 * Phorms_Fields_BooleanField
 *
 * A field representing a boolean choice using a checkbox field.
 * 
 * @category   HTML
 * @package    Phorms
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
class Phorms_Fields_BooleanField extends Phorms_Fields_Field
{
    /**
     * True when the field is checked (true).
     *
     * @var    boolean
     * @access protected
     */
    protected $checked;
    
    /**
     * @author Jeff Ober
     *
     * @param string $label      The field's text label.
     * @param string $help_text  The field's help text.
     * @param array  $validators A list of callbacks to validate the field data.
     * @param array  $attributes A list of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='', 
    array $validators=array(), array $attributes=array()) {
    
        parent::__construct($label, $help_text, $validators, $attributes);
        parent::setValue('on');
        $this->checked = false;
    }
    
    /**
     * Sets the value of the field.
     *
     * @param boolean $value The value to use.
     *
     * @access public
     * @return void
     */
    public function setValue($value)
    {
        $this->checked = (boolean)$value;
    }
    
    /**
     * Returns true if the field is checked.
     *
     * @access public
     * @return boolean
     */
    public function getValue()
    {
        return $this->checked;
    }
    
    /**
     * Returns a new Phorms_Widgets_CheckboxInput.
     *
     * @access public
     * @return Phorms_Widgets_CheckboxInput
     */
    public function getWidget()
    {
        return new Phorms_Widgets_CheckboxInput($this->checked);
    }
    
    /**
     * Returns null.
     *
     * @param mixed $value The value to validate.
     *
     * @access public
     * @return void
     */
    public function validate($value)
    {
        return null;
    }
    
    /**
     * Returns true if the field was checked in the user-submitted data, false
     * otherwise.
     *
     * @param mixed $value The value to import.
     *
     * @access public
     * @return boolean
     */
    public function importValue($value)
    {
        return $this->checked;
    }
    
    /**
     * Returns the value.
     *
     * @param string $value The value to prepare.
     *
     * @access public
     * @return mixed
     */
    public function prepareValue($value)
    {
        return $value;
    }
}
?>