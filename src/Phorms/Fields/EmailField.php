<?php
/**
 * Email address Field
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
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
 
 /**
 * Phorms_Fields_EmailField
 * 
 * A field that only accepts a valid email address.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
class Phorms_Fields_EmailField extends Phorms_Fields_CharField
{
    /**
     * Validates that the value is a valid email address.
     *
     * @param string $value The value to validate.
     *
     * @throws Phorms_Validation_Error
     * @return void
     */
    public function validate($value)
    {
        parent::validate($value);
        
        $re = '@^([-_\.a-zA-Z0-9]+)\@(([-_\.a-zA-Z0-9]+)\.)+[-_\.a-zA-Z0-9]+$@';
        if (!preg_match($re, $value)) {
            throw new Phorms_Validation_Error("Invalid email address.");
        }
    }
}
?>