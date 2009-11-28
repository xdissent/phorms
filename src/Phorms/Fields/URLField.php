<?php
/**
 * URL Field
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
 * Phorms_Fields_URLField
 * 
 * A text field that only accepts a reasonably-formatted URL. Supports HTTP(S)
 * and FTP. If a value is missing the HTTP(S)/FTP prefix, adds it to the final
 * value.
 *
 * @category   HTML
 * @package    Phorms
 * @subpackage Fields
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 *
 * @todo Make this optionally validate that the URL is alive.
 */
class Phorms_Fields_URLField extends Phorms_Fields_CharField
{
    /**
     * Prepares the value by inserting http:// to the beginning if missing.
     *
     * @param string $value The value to prepare.
     *
     * @access public
     * @return string
     */
    public function prepareValue($value)
    {
        if (empty($value)) return '';

        if (!preg_match('@^(http|ftp)s?://@', $value))
            return sprintf('http://%s', $value);
        else
            return $value;
    }
    
    /**
     * Validates that the value is a valid URL (mostly).
     *
     * @param string $value The value to validate.
     *
     * @access public
     * @throws ValidationError
     * @return void
     */
    public function validate($value)
    {
        parent::validate($value);
        
        if (empty($value)) return;
        
        if (!preg_match(
            '@^(http|ftp)s?://(\w+(:\w+)?\@)?(([-_\.a-zA-Z0-9]+)\.)+[-_\.a-zA-Z0-9]+(\w*)@', 
            $value
        )) {
            throw new Phorms_Validation_Error('Invalid URL.');
        }
    }
}
?>