<?php
/**
 * Form Tests
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
require_once 'simpletest/autorun.php';

/**
 * Include and register the Phorms auto-loader.
 */
require_once dirname(__FILE__) . '/../src/Phorms/Utilities/AutoLoader.php';
Phorms_Utilities_AutoLoader::register();

/**
 * A test form.
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
class TestForm extends Phorms_Forms_Form
{
    /**
     * Defines the fields for the form.
     *
     * @access protected
     * @return void
     */
    protected function defineFields() {
        $this->name = new Phorms_Fields_CharField(
            'First name', 
            'Enter your first name.', 
            25, 
            255
        );
        $this->email = new Phorms_Fields_CharField(
            'Email address', 
            'Enter your email address.',
            25, 
            255
        );
    }
}

/**
 * A simple form test case.
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
class SimpleFormTestCase extends UnitTestCase
{
    /**
     * Resets the form class and data before each test.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        /**
         * Valid default data for the form.
         */
        $this->default_data = array(
            'name' => 'Default Name',
            'email' => 'default@example.com'
        );
        
        /**
         * Valid test user data for the form.
         */
        $this->valid_data = array(
            'name' => 'Test User',
            'email' => 'test@testing.com'
        );
        
        /**
         * Invalid test user data for the form.
         */
        $this->invalid_data = array(
            'name' => str_repeat('DEADBEEF', 100)
        );
        
        /**
         * Set the form's test data to valid data.
         */
        TestForm::$test_data = $this->valid_data;
    }
    
    /**
     * Tests the form class constructor.
     *
     * @access public
     * @return void
     */
    public function testFormConstructor()
    {
        $this->assertNotNull(new TestForm());
    }

    /**
     * Tests if the form handles binding user data correctly.
     *
     * @access public
     * @return void
     */
    public function testBoundForm()
    {
        $form = new TestForm(
            Phorms_Forms_Form::TEST,
            false,
            $this->default_data
        );
        
        $this->assertTrue($form->isBound());
    }
    
    /**
     * Tests if the form handles unbound user data correctly.
     *
     * @access public
     * @return void
     */
    public function testUnboundForm()
    {
        /**
         * Empty the test user data to ensure the form shouldn't be bound.
         */
        TestForm::$test_data = array();
        
        $form = new TestForm(
            Phorms_Forms_Form::TEST, 
            false, 
            $this->default_data
        );
        
        $this->assertFalse($form->isBound());
    }
    
    /**
     * Tests if the form validation works with valid data.
     *
     * @access public
     * @return void
     */
    public function testValidForm()
    {
        $form = new TestForm(
            Phorms_Forms_Form::TEST, 
            false, 
            $this->default_data
        );
        
        $this->assertTrue($form->isBound());
        $this->assertTrue($form->isValid());
    }
    
    /**
     * Tests if the form validation works with invalid data.
     *
     * @access public
     * @return void
     */
    public function testInvalidForm()
    {
        /**
         * Set the test data to the invalid data.
         */
        TestForm::$test_data = $this->invalid_data;
        
        $form = new TestForm(
            Phorms_Forms_Form::TEST, 
            false, 
            $this->default_data
        );
        
        $this->assertTrue($form->isBound());
        $this->expectException();
        $this->assertFalse($form->isValid());
    }
    
    /**
     * Tests if the valid form produces cleaned data.
     *
     * @access public
     * @return void
     */
    public function testValidFormCleanedData()
    {
        $form = new TestForm(
            Phorms_Forms_Form::TEST, 
            false, 
            $this->default_data
        );
        
        $this->assertTrue($form->isBound());
        $this->assertTrue($form->isValid());
        $this->assertClone($form->cleanedData(), $this->valid_data);
    }
    
    /**
     * Tests if the invalid form produces cleaned data.
     *
     * @access public
     * @return void
     */
    public function testInvalidFormCleanedData()
    {
        /**
         * Set the test data to the invalid data.
         */
        TestForm::$test_data = $this->invalid_data;
        
        $form = new TestForm(
            Phorms_Forms_Form::TEST, 
            false, 
            $this->default_data
        );
        
        $this->assertTrue($form->isBound());
        $this->expectException();
        $this->assertFalse($form->isValid());
        $this->assertNull($form->cleanedData());
    }
}

?>