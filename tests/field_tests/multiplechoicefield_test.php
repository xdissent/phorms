<?php
/**
 * Multiple Choice Field Test
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
 * @category   Testing
 * @package    Phorms
 * @subpackage Tests
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */

/**
 * Include the base field test.
 */
require_once dirname(__FILE__) . '/field_test.php';

/**
 * Include the choice field test.
 */
require_once dirname(__FILE__) . '/choicefield_test.php';

/**
 * A MultipleChoiceField test case.
 *
 * @category   Testing
 * @package    Phorms
 * @subpackage Tests
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 *
 * @abstract
 */
class MultipleChoiceFieldTestCase extends ChoiceFieldTestCase
{
    /**
     * Sets up the test case before each test.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->field_class = 'Phorms_Fields_MultipleChoiceField';
        $this->invalid_value = array('youandme');
    }
     
    /**
     * Tests the field validation of a valid multiple value.
     *
     * @access public
     * @return void
     */    
    public function testValidMultipleValue()
    {
        $field = new $this->field_class(
            $this->test_label, 
            $this->test_help,
            $this->choices
        );
        
        $val = array_slice(array_keys($this->choices), 0, 2);
                
        $field->setValue($val);
        
        $this->assertTrue($field->isValid());
    }
}
?>