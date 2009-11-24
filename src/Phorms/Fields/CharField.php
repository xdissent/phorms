<?php
/**
 * Character Field
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
 * Phorms_Fields_CharField
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
class Phorms_Fields_CharField extends Phorms_Fields_Field
{
    /**
     * Stores the maximum value length in characters.
     *
     * @var    int
     * @access protected
     */
    protected $max_length;
    
    /**
     * The CharField constructor.
     *
     * @param string $label      The field's text label.
     * @param string $help_text  The field's help text.
     * @param int    $size       The field's size attribute.
     * @param int    $max_length The maximum size in characters.
     * @param array  $validators A list of callbacks to validate the field data.
     * @param array  $attributes A list of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='', $size=25,
    $max_length=255, array $validators=array(), array $attributes=array()) {
        $this->max_length = $max_length;
        $attributes['size'] = $size;
        parent::__construct($label, $help_text, $validators, $attributes);
    }
    
    /**
     * Returns a new CharWidget.
     *
     * @access protected
     * @return Phorms_Widgets_TextInput
     */
    protected function getWidget()
    {
        return new Phorms_Widgets_TextInput();
    }
    
    /**
     * Validates that the value is less than $this->_max_length;
     *
     * @param mixed $value The value to validate.
     *
     * @access protected
     * @throws Phorms_Validation_Error
     * @return void
     */
    protected function validate($value)
    {
        if (strlen($value) > $this->max_length) {
            throw new Phorms_Validation_Error(
                sprintf(
                    'Must be fewer than %d characters in length.', 
                    $this->max_length
                )
            );
        }
    }
    
    /**
     * Imports the value by decoding HTML entities.
     *
     * @param string $value The value to import.
     *
     * @access public
     * @return string
     */
    public function importValue($value)
    {
        return html_entity_decode((string)$value);
    }
}
?>