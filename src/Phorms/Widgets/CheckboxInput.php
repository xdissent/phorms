<?php
/**
 * Checkbox Widget
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
 * Phorms_Widgets_CheckboxInput
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
class Phorms_Widgets_CheckboxInput extends Phorms_Widgets_Widget
{
    /**
     * Stores whether or not the field is checked.
     *
     * @var    boolean
     * @access protected
     */
    protected $checked;
    
    /**
     * The checkbox constructor.
     *
     * @param boolean $checked Whether the field is initially checked.
     *
     * @access public
     * @return void
     */
    public function __construct($checked=false)
    {
        $this->checked = $checked;
    }
    
    /**
     * Returns the field as serialized HTML.
     *
     * @param mixed $value      The form widget's value attribute.
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access protected
     * @return string
     */
    protected function serialize($value, array $attributes=array())
    {
        $attributes['type'] = 'checkbox';
        if ($this->checked) {
            $attributes['checked'] = 'checked';
        }
        return sprintf(
            '<input type="hidden" value="false" name="%s" />' .
            '<input value="true" %s />',  
            $attributes['name'],
            $this->serializeAttributes($attributes)
        );
    }
}
?>