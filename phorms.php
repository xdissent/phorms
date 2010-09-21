<?php 
/**
 * This is where the Phorms library is loaded.
 * @package Phorms
 */
/**
 * Constant used to determine path of includes.
 */
define('PHORMS_ROOT', dirname(__FILE__).'/');

define('PHORMS_AUTOLOAD', FALSE);

if(PHORMS_AUTOLOAD)
{
	function __autoload( $name ) {
		//var_dump($name);
		// Get separated directories
		$path = explode("_", $name);
		// Store the last item as the name
		$name = array_pop($path);
		// Re-build path without last item
		$path = implode('/', $path);
		// Require it
		require_once PHORMS_ROOT.$path.'/'.$name.'.class.php';
	} 
}
else
{
	include(PHORMS_ROOT.'Phorm/Phorm.class.php');
	include(PHORMS_ROOT.'Phorm/Language.class.php');
	include(PHORMS_ROOT.'Phorm/ValidationError.class.php');
	include(PHORMS_ROOT.'Phorm/Field.class.php');
	include(PHORMS_ROOT.'Phorm/Fieldset.class.php');
	include(PHORMS_ROOT.'Phorm/Widget.class.php');
	include(PHORMS_ROOT.'Phorm/FieldsetPhorm.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Text.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Alpha.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Email.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Password.class.php');
	include(PHORMS_ROOT.'Phorm/Field/AlphaNum.class.php');
	include(PHORMS_ROOT.'Phorm/Field/FileUpload.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Regex.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Checkbox.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Hidden.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Scan.class.php');
	include(PHORMS_ROOT.'Phorm/Field/DateTime.class.php');
	include(PHORMS_ROOT.'Phorm/Field/ImageUpload.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Decimal.class.php');
	include(PHORMS_ROOT.'Phorm/Field/MultipleChoice.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Textarea.class.php');
	include(PHORMS_ROOT.'Phorm/Field/DropDown.class.php');
	include(PHORMS_ROOT.'Phorm/Field/Integer.class.php');
	include(PHORMS_ROOT.'Phorm/Field/URL.class.php');
	include(PHORMS_ROOT.'Phorm/Type/File.class.php');
	include(PHORMS_ROOT.'Phorm/Type/Image.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Cancel.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Checkbox.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/FileUpload.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Hidden.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/OptionGroup.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Password.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Radio.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Reset.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Select.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/SelectMultiple.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Submit.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Text.class.php');
	include(PHORMS_ROOT.'Phorm/Widget/Textarea.class.php');
}