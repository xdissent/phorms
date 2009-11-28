<?php
/**
 * Integer Field
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
 * Phorms_Fields_IntegerField
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
class Phorms_Fields_IntegerField extends Phorms_Fields_CharField
{
    /**
     * Stores the max number of digits permitted.
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
     * @param int    $max_digits The maximum number of digits.
     * @param int    $size       The field's size attribute.
     * @param array  $validators A list of callbacks to validate the field data.
     * @param array  $attributes A list of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='',  $max_digits=20,
    $size=25, array $validators=array(), array $attributes=array()) {
        parent::__construct(
            $label, 
            $help_text, 
            $size, 
            $max_digits, 
            $validators, 
            $attributes
        );
        $this->max_digits = $max_digits;
    }
    
    /**
     * Validates that the value is parsable as an integer and that it is fewer
     * than $this->max_digits digits.
     *
     * @param string $value The value to validate.
     *
     * @throws Phorms_Validation_Error
     * @return void
     */
    public function validate($value)
    {
        if (preg_match('/\D/', $value) 
            || strlen((string)$value) > $this->max_digits
        ) {
            throw new Phorms_Validation_Error(
                sprintf(
                    'Must be a number with fewer than %d digits.',
                    $this->max_digits
                )
            );
        }
    }
    
    /**
     * Parses the value as an integer.
     *
     * @param string $value The value to import.
     *
     * @return integer
     */
    public function importValue($value)
    {
        return (int)(html_entity_decode((string)$value));
    }
}

