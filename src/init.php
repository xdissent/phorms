<?php

/*
 * This file is part of Phorms (freely inspired from Swift Mailer's autoload).
 * (c) 2009-2010 Lété Thomas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * General utility class in Phorms, not to be instantiated.
 * 
 * @package Swift
 * 
 * @author Lété Thomas
 */
abstract class PhormInit
{

	/** Phorms Version number generated during dist release process */
	const VERSION = '0.9.4';

	/**
	 * Internal autoloader for spl_autoload_register().
	 * 
	 * @param string $class
	 */
	public static function autoload($class)
	{
		//Don't interfere with other autoloaders
		if( 0 !== strpos($class, 'Phorms') )
		{
			return false;
		}

		require_once('phorms.php');
		/* $path = dirname(__FILE__).'/'.str_replace('_', '/', $class).'.php';

		  if (!file_exists($path))
		  {
		  return false;
		  }

		  require_once $path; */
	}

	/**
	 * Configure autoloading using Phorms.
	 *
	 * This is designed to play nicely with other autoloaders.
	 */
	public static function registerAutoload()
	{
		spl_autoload_register(array( 'PhormInit', 'autoload' ));
	}

}

?>