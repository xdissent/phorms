<?php

/**
 * PhormValidation
 *
 * Validation class to provide built-in validation methods.
 * 
 * @author Thomas Lété
 * @package default
 * @see Phorm
 **/
 
class PhormValidation
{
    const Required = 'PhormValidation::required';
	
    public static function required($value)
	{
		if ($value == '' || is_null($value))
			throw new ValidationError($GLOBALS['phorms_tr']['validation_required']);
	}
}

?>