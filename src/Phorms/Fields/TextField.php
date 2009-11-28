<?php
/**
 * Text Field
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
 * Phorms_Fields_TextField
 * 
 * A large text field using a textarea tag.
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
class Phorms_Fields_TextField extends Phorms_Fields_Field
{
    /**
     * The text field constructor.
     *
     * @param string $label      The field's text label.
     * @param string $help_text  The field's help text.
     * @param int    $rows       The number of rows.
     * @param int    $cols       The number of columns.
     * @param array  $validators A list of callbacks to validate the field data.
     * @param array  $attributes A list of key/value pairs representing HTML 
     *                           attributes.
     *
     * @access public
     * @return void
     */
    public function __construct($label, $help_text='', $rows=40, $cols=60, 
    array $validators=array(), array $attributes=array()) {
        $attributes['cols'] = $cols;
        $attributes['rows'] = $rows;
        parent::__construct($label, $help_text, $validators, $attributes);
    }
    
    /**
     * Returns a new TextWidget.
     *
     * @return Phorms_Widgets_Textarea
     */
    protected function getWidget()
    {
        return new Phorms_Widgets_Textarea();
    }
    
    /**
     * Returns null.
     *
     * @param mixed $value The value to validate.
     *
     * @access protected
     * @return boolean
     */
    protected function validate($value)
    {
        return true;
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