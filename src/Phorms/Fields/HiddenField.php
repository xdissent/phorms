<?php
/**
 * Hidden Field
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
 * Phorms_Fields_HiddenField
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
class Phorms_Fields_HiddenField extends Phorms_Fields_CharField
{
    /**
     * The field constructor.
     *
     * @param array $validators A list of callbacks to validate the field data.
     * @param array $attributes A list of key/value pairs representing HTML 
     *                          attributes.
     *
     * @access public
     * @return void
     */
    public function __construct(array $validators=array(), 
    array $attributes=array()) {
        parent::__construct('', '', 25, 255, $validators, $attributes);
    }
    
    /**
     * Does not print out a label.
     *
     * @access public
     * @return string
     */
    public function label()
    {
        return '';
    }
    
    /**
     * Returns an empty string to suppress the help text.
     *
     * @access public
     * @return string
     */
    public function getHelpText()
    {
        return '';
    }
    
    /**
     * Returns a new Phorms_Widgets_HiddenInput.
     *
     * @access protected
     * @return Phorms_Widgets_HiddenInput
     */
    protected function getWidget()
    {
        return new Phorms_Widgets_HiddenInput();
    }
}
?>