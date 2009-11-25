<?php
/**
 * Password Widget
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
 * @subpackage Widgets
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
 
/**
 * Phorms_Widgets_PasswordInput
 * 
 * @category   HTML
 * @package    Phorms
 * @subpackage Widgets
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
class Phorms_Widgets_PasswordInput extends Phorms_Widgets_Widget
{
    /**
     * Returns the field as serialized HTML.
     *
     * @param mixed $value      The form widget's value attribute.
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value
     *
     * @access protected
     * @return string
     */
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'password';
        return parent::serialize($value, $attributes);
    }
}
?>