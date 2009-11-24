<?php
/**
 * Auto Loader
 *
 * This file defines an auto loader class that handles the automatic loading 
 * of undefined class names in PHP scripts.
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
 * @category   Utilities
 * @package    Phorms
 * @subpackage Utilities
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Jeff Ober
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */

/**
 * A generic auto loader that searches for classes based on their name.
 *
 * To install this auto loader, include this file and call the loader's static 
 * register method.
 *
 * @category   Utilities
 * @package    Phorms
 * @subpackage Utilities
 * @author     Jeff Ober <jeffober@gmail.com>
 * @author     Greg Thornton <xdissent@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://www.artfulcode.net/articles/phorms-a-php-form-library/
 */
class Phorms_Utilities_AutoLoader
{
    /**
     * The extension to use when locating class names.
     * 
     * @var    string
     * @access protected
     * @static
     */
    protected static $extension = '.php';
    
    /**
     * A flag to indicate if this auto loader has been registered.
     *
     * @var    string
     * @access protected
     * @static
     */
    protected static $registered = false;
    
    /**
     * Registers the auto loader with PHP's auto loading system.
     * 
     * @access public
     * @static
     * @return boolean
     */
    public static function register()
    {
        /**
         * The function 'get_called_class' is required to find the
         * name of the class or subclass that called this static
         * method.
         */
        if (!static::$registered) {
            static::$registered = spl_autoload_register(
                array(get_called_class(), 'autoLoad')
            );
        }
        return static::$registered;
    }

    /**
     * Loads the file in which a class is defined based on its name.
     *
     * This static method includes the file in which a class is defined by
     * translating the class name into a file path. Class names should follow
     * the PEAR standard convention of 'Package_Subpackage_ClassName', although
     * other schemes ('Package_ClassName' for the lazy) may work as well. The
     * translated path will be prefixed with the absolute path of the directory
     * containing this file, minus the number of levels suggested by this 
     * class's name. This is done to compensate for the package and (possible)
     * subpackage directories in which this file will be stored.
     *
     * For example, if this class is located in 
     * '/home/xdissent/Code/Phorms/Utilities/AutoLoader.php' and named 
     * 'Phorms_Utilities_AutoLoader', the prefix used would be 
     * '/home/xdissent/Code' and autoloading the class 
     * 'Package_Subpackage_ClassName' would include the file 
     * '/home/xdissent/Code/Package/Subpackage/ClassName.php'.
     *
     * This method must remain public to be used with 'spl_autoload_register'.
     *
     * @param string $class The name of the class to try to load.
     *
     * @access public
     * @static
     * @return boolean
     *
     * @todo Make subclasses calculate the path prefix correct.
     */
    public static function autoLoad($class)
    {
        /**
         * Determine the absolute path prefix to use.
         */
        $num_name_elements = count(explode('_', get_called_class()));
        $prefix = explode(DIRECTORY_SEPARATOR, __FILE__);
        
        /**
         * Remove the class name components from the prefix.
         */
        $prefix = array_slice($prefix, 0, count($prefix) - $num_name_elements);
        
        /**
         * Collapse the prefix array into an absolute path prefix.
         */
        $prefix = implode(DIRECTORY_SEPARATOR, $prefix);
         
        /**
         * Split the class name by underscores to find the path components.
         */
        $path = explode('_', $class);
        
        /**
         * Add the prefix as a path component.
         */
        array_unshift($path, $prefix);
        
        /**
         * Combine the path elements to construct the OS compatible path string.
         */
        $path = implode(DIRECTORY_SEPARATOR, $path);
        
        /**
         * Add this class's defined extension to get the final include path.
         */
        $path .= static::$extension;
        
        /**
         * Check to see if the include file exists before including.
         */
        if (!file_exists($path)) {
            /**
             * If the file isn't there, pass control to the next autoloader.
             */
            return false;
        }
        
        /**
         * Include the file that should contain the requested class. If it does
         * *not* include the requested class, the next autoloader will try to
         * locate it.
         */
        include_once $path;

        return true;
    }
}
?>