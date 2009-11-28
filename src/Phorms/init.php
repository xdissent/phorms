<?php
/**
 * Phorms initialization.
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
 * @category  HTML
 * @package   Phorms
 * @author    Jeff Ober <jeffober@gmail.com>
 * @author    Greg Thornton <xdissent@gmail.com>
 * @copyright 2009 Jeff Ober
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */

/**
 * Load the Phorms auto loader and register it.
 */
require_once dirname(__FILE__) . '/Utilities/AutoLoader.php';
Phorms_Utilities_AutoLoader::register();

?>