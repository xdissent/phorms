<?php
/**
 * Widget
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
 * Phorms_Widgets_Widget
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
class Phorms_Widgets_Widget
{
    /**
     * Serializes an array of key=>value pairs as an HTML attributes string.
     *
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access protected
     * @return string
     */
    protected function serializeAttributes(array $attributes=array())
    {
        $attr = array();
        foreach ($attributes as $key => $val) {
            $attr[] = sprintf('%s="%s"', $key, $val);
        }
        return implode(' ', $attr);
    }
    
    /**
     * Serializes the widget as an HTML form input.
     *
     * @param string $value      The form widget's value.
     * @param array  $attributes Key=>value pairs corresponding to HTML 
     *                           attributes' name=>value.
     *
     * @access protected
     * @return string
     */
    protected function serialize($value, array $attributes=array())
    {
        return sprintf(
            '<input value="%s" %s />', 
            $value, 
            $this->serializeAttributes($attributes)
        );
    }
    
    /**
     * Casts a value to a string and encodes it for HTML output.
     *
     * @param mixed $str The string to clean.
     *
     * @access protected
     * @return a decoded string
     */
    protected function cleanString($str)
    {
        return htmlentities((string)$str);
    }
    
    /**
     * Returns the field as serialized HTML.
     *
     * @param mixed $value      The form widget's value attribute.
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access public
     * @return string the serialized HTML
     */
    public function html($value, array $attributes=array())
    {
        $value = htmlentities((string)$value);
        foreach ($attributes as $key => $val) {
            $attributes[htmlentities((string)$key)] = htmlentities(
                (string)$val
            );
        }
        return $this->serialize($value, $attributes);
    }
}
?>