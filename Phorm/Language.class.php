<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 */
/**
 * Phorm_Language
 *
 * Responsible for the messages of the Phorms.
 *
 * @author George Petsagourakis <petsagouris@gmail.com>
 * @package Phorms
 */
class Phorm_Language
{
	/**
	 * The strings in the selected language.
	 * @var array
	 */
	private $lang = array();

	/**
	 * The strings in the fallback language (English recommended).
	 * @var array
	 */
	private $fallback = array();

	/**
	 * Public constructor makes arrangements for the strings to load.
	 *
	 * @param string $lang the language code to load.
	 */
	public function __construct($lang='en')
	{
		$english = $this->load_strings('en');
		if($lang!=='en')
		{
			$this->lang = $this->load_strings($lang);
			$this->fallback = $english;
		}
		else
		{
			$this->lang = $english;
		}
	}

	/**
	 * Public getter for the language strings.
	 *
	 * @param string $name the language key for the wanted string.
	 */
	public function __get($name)
	{
		if(substr($name, 0, 2) == 'a:')
		{
			$args = unserialize( $name );
			$name = array_shift($args);

		}

		if(!strstr($name,' '))
		{
			if (isset($this->lang[$name]))
			{
				$name = $this->lang[$name];
			}
			elseif( isset($this->fallback[$name]) )
			{
				$name = $this->fallback[$name];
			}
			else
			{
				throw new Exception('Phorms could not retrieve string for "'.$name.'", please review your code.');
			}
		}

		if(!empty($args))
		{
			array_unshift($args, $name);
			return call_user_func_array( 'sprintf', $args );
		}

		return $name;

	}

	/**
	 * Loads the strings from the 'lang' directory.
	 *
	 * @param string $code the language code to load the strings for.
	 */
	private function load_strings($code)
	{
		$lang = array();
		include('lang/'.$code.'.php');
		return $lang;
	}
}