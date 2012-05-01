<?php
error_reporting( E_ALL);

require_once('../phorms.php');

define('MAX_HEIGHT', 100);
define('MAX_WIDTH', 100);
define('UPLOAD_TARGET_DIRECTORY', '~/tmp');

function check_image_size($value)
{
	if ($value !== false)
	{
		if ($value->width > MAX_WIDTH)
		{
			throw new Phorm_ValidationError(serialize( array("Image is wider than %d pixels.", MAX_WIDTH) ) );
		}
		if ($value->height > MAX_HEIGHT)
		{
			throw new Phorm_ValidationError(serialize( array( "Image is taller than %d pixels.", MAX_HEIGHT) ));
		}
	}
}

class UploadForm extends Phorm_Phorm
{
	protected $path;
	
	public function __construct($target_directory, $method='get', $multi_part=FALSE, $data=array())
	{
		if (!file_exists($target_directory))
		{
			trigger_error('invalid directory', E_USER_ERROR);
		}
		
		if (!is_writable($target_directory))
		{
			trigger_error('no permissions to target directory', E_USER_ERROR);
		}
		
		$this->path = $target_directory;
		parent::__construct($method, $multi_part, $data);
	}
	
	protected function define_fields()
	{
		$this->image = new Phorm_Field_ImageUpload('Photo', 1024 * 1024, array('check_image_size'));
		$this->caption = new Phorm_Field_Text('Caption', 50, 255);
	}
	
	public function save()
	{
		$data = $this->cleaned_data();
		$image_path = $data['image']->move_to($this->path);
		// do something with the data like saving it to a database...
		return $image_path;
	}

	public function report()
	{
		var_dump($this->cleaned_data());
	}
}

// Init form
$form = new UploadForm(UPLOAD_TARGET_DIRECTORY, 'post', TRUE);

// If the form is bound and valid, move the file to a more permanent location
$saved = NULL;
$file_error = NULL;
$photo_path = NULL;

if ( ( $valid = $form->is_valid() ) && $form->bound )
{
	try
	{
		$photo_path = $form->save();
		$saved = TRUE;
	}
	catch (Exception $e)
	{
		$file_error = $e->message;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Image Upload Form example for Phorm</title>
		<link rel="stylesheet" href="assets/style.css" type="text/css" />
	</head>
	<body>
		<?php echo $form->open(); ?>
		<h2>Example file drop</h2>
		<?php if( $form->has_errors() ) { ?>
		<div class="phorm_error">Please correct the following errors.</div>
		<?php } elseif ($saved) { ?>
		<div class="phorm_message">Your photo has been saved to <?php echo $photo_path ?>.</div>
		<?php } elseif ($saved === false) { ?>
		<div class="phorm_error"><?php echo $file_error ?></div>
		<?php } ?>
		<?php echo $form; ?>
		<input type="submit" value="Submit" />
		<?php echo $form->close(); ?>

		<h4>Raw POST data:</h4>
		<?php var_dump($_POST); ?>
		<hr />
		<?php
		if( $form->bound && $form->is_valid() )
		{
			echo '<h4>Processed and cleaned form data:</h4>';
			$form->report();
		}
		elseif( $form->has_errors() )
		{
			echo '<h4>Errors:</h4>';
			var_dump($form->get_errors());
		}
		else
		{
			echo '<p><em>The form is unbound.</em></p>';
		}
		?>
	</body>
</html>
