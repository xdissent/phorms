<?php
/**
 * BooleanField Test
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
 * A BooleanField test case.
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
class BooleanFieldTestCase extends FieldTestCase
{
    /**
     * Sets up the test case before each test.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->field_class = 'Phorms_Fields_BooleanField';
        $this->test_label = 'Test field';
        $this->test_help = 'Enter your test value.';
        $this->entities_help = 'Enter & win.';
        $this->valid_value = true;
        $this->invalid_value = $this->valid_value;
    }
    
    /**
     * Tests the field validation of an invalid value.
     *
     * Overridden because BooleanField doesn't currently provide
     * internal validation of any sort.
     *
     * @access public
     * @return void
     */    
    public function testInvalidValue()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->invalid_value);
        
        $this->assertTrue($field->isValid());
    }
    
    /**
     * Tests the field validation errors of an invalid value.
     *
     * Overridden because BooleanField doesn't currently provide
     * internal validation of any sort.
     *
     * @access public
     * @return void
     */    
    public function testInvalidValueErrors()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->invalid_value);

        $this->assertTrue($field->isValid());
        $this->assertFalse($field->getErrors());
    }
}
?>