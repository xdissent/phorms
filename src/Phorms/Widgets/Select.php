<?php
/**
 * Select Widget
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
 * Phorms_Widgets_Select
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
class Phorms_Widgets_Select extends Phorms_Widgets_Widget
{
    /**
     * The choices for this field as an array of actual=>display values.
     *
     * @var    array
     * @access protected
     */
    protected $choices;
    
    /**
     * The select constructor.
     *
     * @param array $choices The choices as an array of actual=>display values.
     *
     * @access public
     * @return void
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }
    
    /**
     * Returns the field as serialized HTML.
     *
     * @param mixed $value      The form widget's selected value.
     * @param array $attributes Key=>value pairs corresponding to HTML 
     *                          attributes' name=>value.
     *
     * @access protected
     * @return string
     */
    protected function serialize($value, array $attributes=array())
    {
        $options = array();
        foreach ($this->choices as $actual => $display) {
            $option_attributes = array('value' => $this->cleanString($actual));
            if ($actual == $value) {
                $option_attributes['selected'] = 'selected';
            }
            $options[] = sprintf(
                '<option %s>%s</option>',
                $this->serializeAttributes($option_attributes),
                $this->cleanString($display)
            );
        }
        
        return sprintf(
            '<select %s>%s</select>',
            $this->serializeAttributes($attributes),
            implode($options)
        );
    }
}
?>