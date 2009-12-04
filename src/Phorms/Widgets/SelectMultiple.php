<?php
/**
 * Select Multiple Widget
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
 * Phorms_Widgets_SelectMultiple
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
class Phorms_Widgets_SelectMultiple extends Phorms_Widgets_Select
{
    /**
     * Returns the rendered html for a field.
     *
     * @param array $value      An array of the field's selected values.
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access public
     * @return string
     */
    public function html($value, array $attributes=array())
    {
        if (is_null($value)) $value = array();
        
        foreach ($attributes as $key => $val) {
            $attributes[htmlentities((string)$key)] = htmlentities((string)$val);
        }
        
        return $this->serialize($value, $attributes);
    }
    
    /**
     * Returns the field as serialized HTML.
     *
     * @param array $value      The form widget's values
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access protected
     * @return string
     */
    protected function serialize($value, array $attributes=array())
    {
        if (is_null($value)) {
            $value = array();
        }
        
        if (!is_array($value)) {
            $value = array($value);
        }
        
        $options = array();
        foreach($this->choices as $actual => $display)
        {
            $option_attributes = array('value' => $this->cleanString($actual));
            if (in_array($actual, $value)) {
                $option_attributes['selected'] = 'selected';
            }
            $options[] = sprintf(
                '<option %s>%s</option>',
                $this->serializeAttributes($option_attributes),
                $this->cleanString($display)
            );
        }
        
        return sprintf(
            '<select multiple="multiple" %s>%s</select>',
            $this->serializeAttributes($attributes),
            implode($options)
        );
    }
}
?>