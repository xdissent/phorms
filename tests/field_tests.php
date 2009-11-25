<?php
/**
 * Field Tests
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


class FieldTests extends TestSuite
{
    function FieldTests()
    {
        $this->TestSuite('Field Tests');

        $this->addFile(dirname(__FILE__) . '/field_tests/charfield_test.php');

        $this->addFile(dirname(__FILE__) . '/field_tests/hiddenfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/passwordfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/textfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/emailfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/integerfield_test.php');

        $this->addFile(dirname(__FILE__) . '/field_tests/urlfield_test.php');
    }
}
?>