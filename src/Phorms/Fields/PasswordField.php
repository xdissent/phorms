<?php
/**
 * Password Field
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
 * Phorms_Fields_PasswordField
 *
* A password field that uses a user-specified hash function to import values.
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
class Phorms_Fields_PasswordField extends Phorms_Fields_CharField
{
    /**
     * The hash function to encode the user-submitted value.
     *
     * @var    callback
     * @access protected
     */
    protected $hash_function;
    
    /**
     * The password field constructor. 
     *
     * @param string   $label         The field's text label.
     * @param string   $help_text     The hash function to use.
     * @param callback $hash_function A (string) function or array (instance, 
     *                                string method) callback.
     * @param int      $size          The field's size attribute.
     * @param int      $max_length    The maximum size in characters.
     * @param array    $validators    A list of callbacks to validate the field 
     *                                data.
     * @param array    $attributes    Alist of key/value pairs representing HTML 
     *                                attributes.
     *
     * @access public
     * @return void
     *
     * @todo Remove the hashing functionality.
     */
    public function __construct($label, $help_text='', $hash_function='crypt', 
    $size=25, $max_length=255, $validators=array(), $attributes=array()) {
    
        $this->hash_function = $hash_function;
        parent::__construct(
            $label, 
            $help_text, 
            $size, 
            $max_length, 
            $validators, 
            $attributes
        );
    }
    
    /**
     * Returns a Phorms_Widgets_PasswordInput.
     *
     * @access public
     * @return Phorms_Widgets_PasswordInput
     */
    public function getWidget()
    {
        return new Phorms_Widgets_PasswordInput();
    }
    
    /**
     * Returns a hash-encoded value.
     *
     * @param string $value The value to import.
     *
     * @access public
     * @return string
     */
    public function importValue($value)
    {
        return call_user_func($this->hash_function, $value);
    }
}
?>