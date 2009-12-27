<?php

error_reporting(6143|2048);

require_once('../src/phorms.php');

define('MAX_HEIGHT', 100);
define('MAX_WIDTH', 100);


function check_image_size($value)
{
	if ($value !== false)
	{
		if ($value->width > MAX_WIDTH)
			throw new ValidationError(sprintf("Image is wider than %d pixels.", MAX_WIDTH));
		if ($value->height > MAX_HEIGHT)
			throw new ValidationError(sprintf("Image is taller than %d pixels.", MAX_HEIGHT));
	}
}

class UploadForm extends Phorm
{
	protected $path;
	
	public function __construct($target_directory, $method=Phorm::GET, $multi_part=false, $data=array())
	{
		if (!file_exists($target_directory))
			trigger_error('invalid directory', E_USER_ERROR);
		
		if (!is_writable($target_directory))
			trigger_error('no permissions to target directory', E_USER_ERROR);
		
		$this->path = $target_directory;
		parent::__construct($method, $multi_part, $data);
	}
	
	protected function define_fields()
	{
		$this->image = new ImageField('Photo', 1024 * 1024, array('check_image_size'));
		$this->caption = new TextField('Caption', 50, 255);
	}
	
	public function save()
	{
		$data = $this->cleaned_data();
		$image_path = $data['image']->move_to($this->path);
		// do something with the data like saving it to a database...
		return $image_path;
	}
}

// Init form
$form = new UploadForm('/tmp', Phorm::POST, true);

// If the form is bound and valid, move the file to a more permanent location
$saved = null;
$file_error = null;
$photo_path = null;
if ( ( $valid = $form->is_valid() ) && $form->is_bound() )
{
	try
	{
		$photo_path = $form->save();
		$saved = true;
	}
	catch (Exception $e)
	{
		$file_error = $e->message;
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>Profile form</title>
		<script src="../src/javascript/scriptaculous/lib/prototype.js" type="text/javascript"></script>
		<script src="../src/javascript/scriptaculous/src/effects.js" type="text/javascript"></script>
		<script src="../src/javascript/validation.js" type="text/javascript"></script>
		<style type="text/css">
			form .phorm_help {
				margin: 0;
				padding: 2px;
				font-size: 10pt;
				font-style: oblique;
				color: #666;
				display: block;
			}
			
			form
			{
				width: 60%;
			}

			form p
			{
				margin: 2px 0;
			}

			/* fieldset , legend */
			form fieldset
			{
				margin-bottom: 10px;
				border: #CCC 1px solid;
			}

			form fieldset legend
			{
				padding: 0 10px;
				border-left: #CCC 1px solid;
				border-right: #CCC 1px solid;
				font-size: 1.2em;
				color: #999;
			}

			/* Label */
			form label
			{
				background-color: #FFCC66;
				display: block;
				width: 39%;
				float: left;
				padding-right: 1%;
				text-align: right;
				letter-spacing: 1px;
			}

			/* Input */
			form input, form select
			{
				margin-left: 1%;
				border: #CCC 1px solid;
			}
			
			form input[type="text"], form select
			{
				width: 58%;
			}

			/* Textarea */
			form textarea
			{
				margin-left: 1%;
				width: 58%;
				border: #CCC 1px solid;
			}

			/* button submit */
			form input[type="submit"]
			{
				border: #DDEEFF 1px solid;
				width: 27%;
			}

			form input[type="submit"]:hover
			{
				background-color: #66CC33;
				cursor: pointer;
			}

			form input[type="reset"]
			{
				border: #DDEEFF 1px solid;
				width: 27%;
			}

			form input[type="reset"]:hover
			{
				background-color: #E6484D;
				cursor: pointer;
			}

			form .required
			{
				border-width: 2px;
			}
			
			/* Validation */
			form .validation-advice {
				margin: 5px 0;
				padding: 5px;
				background-color: #FF3300;
				color : #FFF;
				font-weight: bold;
			}

		</style>
	</head>
		
		<?= $form->open() ?>
		<h2>Example file drop</h2>
			<?php if ( $form->has_errors() ): ?>
			<p class="phorm_error">Please correct the following errors.</p>
			<?php elseif ($saved): ?>
			<p><em>Your photo has been saved to <?= $photo_path ?>.</em></p>
			<?php elseif ($saved === false): ?>
			<p class="phorm_error"><?= $file_error ?></p>
			<?php endif ?>
		<?= $form ?>
		<?= $form->buttons() ?>
		<?= $form->close() ?>

		<h4>Raw POST data:</h4>
		<?php var_dump($_POST); ?>

		<hr />

		<?php if ($form->is_bound() && $valid): ?>
			<h4>Processed and cleaned form data:</h4>
			<? var_dump($form->cleaned_data()) ?>
		<?php elseif ($form->has_errors()): ?>
			<h4>Errors:</h4>
			<?php var_dump($form->get_errors()); ?>
		<?php else: ?>
			<p><em>The form is unbound.</em></p>
		<?php endif ?>

	</body>
</html>