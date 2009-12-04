<?php
/**
 * Multiple Choice Field
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
 * Phorms_Fields_MultipleChoiceField
 * 
 * A compound field offering multiple choices as a select multiple tag.
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
class Phorms_Fields_MultipleChoiceField extends Phorms_Fields_ChoiceField
{
    /**
     * Specifies that this field's name attribute must be post-fixed by [].
     *
     * @var boolean
     * @access public
     */
    public $multi_field = true;
    
    /**
     * Returns a new Phorms_Widgets_SelectMultiple.
     *
     * @access public
     * @return Phorms_Widgets_SelectMultiple
     */
    public function getWidget()
    {
        return new Phorms_Widgets_SelectMultiple($this->choices);
    }
    
    /**
     * Pre-processes a value for validation, handling magic quotes if used.
     *
     * @param array $value The value from the form array.
     *
     * @access protected
     * @return string
     */
    protected function prepareValue($value)
    {
        if (!is_array($value)) {
            return array();
        }
        
        foreach ($value as $key => $val) {
            $value[$key] = (get_magic_quotes_gpc()) ? stripslashes($val) : $val;
        }
        return $value;
    }
    
    /**
     * Validates that each of the selected values is present in $this->choices.
     *
     * @param string $value The value to validate.
     *
     * @access public
     * @throws Phorms_Validation_Error
     * @return void
     */
    public function validate($value)
    {
        if (!is_array($value)) {
            throw new Phorms_Validation_Error('Invalid selection');
        }
        
        foreach ($value as $v) {
            if (!in_array($v, array_keys($this->choices))) {
                throw new Phorms_Validation_Error('Invalid selection.');
            }
        }
    }
    
    /**
     * Imports the value array by decoding any HTML entities. 
     * Returns the "actual" value of the options selected.
     *
     * @param string $value The value to import.
     *
     * @access public
     * @return string
     */
    public function importValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => &$val) {
                $val = html_entity_decode($val);
            }
        }
        return $value;
    }
}
?>