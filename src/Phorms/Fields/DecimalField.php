<?php
/**
 * Decimal Field
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
 * Phorms_Fields_DecimalField
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
class Phorms_Fields_DecimalField extends Phorms_Fields_CharField
{
    /**
     * Stores the max number of decimal places permitted.
     *
     * @var integer
     * @access protected
     */
    protected $max_digits;
    
    /**
     * The integer field constructor.
     *
     * @param string $label      The field's text label.
     * @param string $help_text  The field's help text.
     * @param int    $precision  The maximum number of decimal places.
     * @param int    $size       The field's size attribute.
     * @param int    $max_length The maximum number of digits.
     * @param array  $validators A list of callbacks to validate the field data.
     * @param array  $attributes A list of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='', $precision=5, 
    $size=25, $max_length=255, array $validators=array(), 
    array $attributes=array())
    {
        parent::__construct(
            $label, 
            $help_text, 
            $size, 
            $max_length, 
            $validators, 
            $attributes
        );
        $this->_precision = $precision;
    }
    
    /**
     * Validates that the value is parsable as a float.
     *
     * @param string value The value to validate.
     *
     * @access public
     * @throws Phorms_Validation_Error
     * @return void
     */
    public function validate($value)
    {
        if (!is_numeric($value))
            throw new Phorms_Validation_Error("Invalid decimal value.");
    }
    
    /**
     * Returns the parsed float, rounded to $this->_precision digits.
     *
     * @param string $value The value to import.
     *
     * @access public
     * @return float
     */
    public function importValue($value)
    {
        return round((float)(html_entity_decode($value)), $this->_precision);
    }
}
?>