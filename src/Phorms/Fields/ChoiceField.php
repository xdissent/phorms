<?php
/**
 * Choice Field
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
 * Phorms_Fields_ChoiceField
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
class Phorms_Fields_ChoiceField extends Phorms_Fields_Field
{
    /**
     * An array storing the drop-down's choices.
     *
     * @var    array
     * @access protected
     */
    protected $choices;
    
    /**
     * The choice field constructor.
     *
     * @param string $label     The field's text label.
     * @param string $help_text The field's help text.
     * @param array $choices    A list of choices as 
     *                          actual_value=>display_value.
     * @param array $validators A list of callbacks to validate the field data.
     * @param array $attributes A list of key/value pairs representing HTML 
     *                          attributes.
     */
    public function __construct($label, $help_text='', array $choices, 
    array $validators=array(), array $attributes=array()) {
        parent::__construct($label, $help_text, $validators, $attributes);
        $this->choices = $choices;
    }
    
    /**
     * Returns a new Phorms_Widgets_Select.
     *
     * @access public
     * @return Phorms_Widgets_Select
     */
    public function getWidget()
    {
        return new Phorms_Widgets_Select($this->choices);
    }
    
    /**
     * Validates that $value is present in $this->choices.
     *
     * @param string $value The value to validate.
     *
     * @access public
     * @throws Phorms_Validation_Error
     * @return void
     */
    public function validate($value)
    {
        if (!in_array($value, array_keys($this->choices))) {
            throw new Phorms_Validation_Error('Invalid selection.');
        }
    }
    
    /**
     * Imports the value by decoding any HTML entities. Returns the "actual"
     * value of the option selected.
     *
     * @param string $value The value to import.
     *
     * @access public
     * @return string the decoded string
     */
    public function importValue($value)
    {
        return html_entity_decode((string)$value);
    }
}
?>