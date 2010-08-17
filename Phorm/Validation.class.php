<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 */
/**
 * Phorm_Validation
 *
 * Validation class to provide built-in validation methods.
 *
 * @author Thomas Lété
 * @package Phorms
 * @see Phorm
 */
class Phorm_Validation
{

	const Required = 'Phorm_Validation::required';

	public static function required($value)
	{
		global $phorms_tr;

		if( $value == '' || is_null($value) )
		{
			throw new PhormException($phorms_tr['validation_required']);
		}
	}

}