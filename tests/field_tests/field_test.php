<?php
/**
 * Field Test Base
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
 * Include SimpleTest auto-runner.
 */
require_once dirname(__FILE__) . '/../simpletest/autorun.php';

/**
 * Init Phorms.
 */
require_once dirname(__FILE__) . '/../../src/Phorms/init.php';

/**
 * A field test case base class.
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
abstract class FieldTestCase extends UnitTestCase
{
    /**
     * Sets up the test case before each test.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->field_class = 'TestField';
        $this->test_label = 'Test field';
        $this->test_help = 'Enter your test value.';
        $this->entities_help = 'Enter & win.';
        $this->valid_value = 'Test value';
        $this->invalid_value = str_repeat('DEADBEEF', 100);
    }
       
    /**
     * Tests the field class constructor.
     *
     * @access public
     * @return void
     */
    public function testFieldConstructor()
    {
        $this->assertNotNull(new $this->field_class($this->test_label));
    }
    
    /**
     * Tests the help text of the field.
     *
     * @access public
     * @return void
     */
    public function testHelpText()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $help_pattern = '/<p[^>]*>' . 
            str_replace('.', '\.', $this->test_help) . 
            '<\/p>/';

        $this->assertPattern($help_pattern, $field->getHelpText());
    }
    
    /**
     * Tests the help text of the field with html entities.
     *
     * @access public
     * @return void
     */
    public function testHelpTextEntities()
    {
        $field = new $this->field_class($this->test_label, $this->entities_help);
        
        $help_pattern = '/<p[^>]*>' . 
            str_replace('.', '\.', htmlentities($this->entities_help)) .
            '<\/p>/';

        $this->assertPattern($help_pattern, $field->getHelpText());
    }

    /**
     * Tests the field validation of a valid value.
     *
     * @access public
     * @return void
     */    
    public function testValidValue()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->valid_value);
        
        $this->assertTrue($field->isValid());
    }
    
    /**
     * Tests the field validation of an invalid value.
     *
     * @access public
     * @return void
     */    
    public function testInvalidValue()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->invalid_value);
        
        $this->assertFalse($field->isValid());
    }
    
    /**
     * Tests the field validation errors of a valid value.
     *
     * @access public
     * @return void
     */    
    public function testValidValueErrors()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->valid_value);
        
        $this->assertTrue($field->isValid());
        $this->assertFalse($field->getErrors());
    }
    
    /**
     * Tests the field validation errors of an invalid value.
     *
     * @access public
     * @return void
     */    
    public function testInvalidValueErrors()
    {
        $field = new $this->field_class($this->test_label, $this->test_help);
        
        $field->setValue($this->invalid_value);

        $this->assertFalse($field->isValid());
        $this->assertTrue($field->getErrors());
    }
}
?>