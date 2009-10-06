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
	
	protected function defineFields()
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

?><html>
	<body>
		<style>
			table { border: 1px solid #ccc; padding: 2px 4px; }
			th { vertical-align: top; text-align: right; }
			td { vertical-align: top; }
			thead th { text-align: center; font-size: 16pt; background-color: #ccc; }
			.phorm_error { color: #bb0000; font-size: 10pt; text-align: left; font-style: oblique; }
			.phorm_help { margin: 0; padding: 2px; font-size: 10pt; font-style: oblique; color: #666; }
		</style>
		
		<?= $form->open() ?>
		<table>
			<thead>
				<tr><th colspan="2">Example file drop</th></tr>
				<?php if ( $form->has_errors() ): ?>
				<tr><th class="phorm_error" colspan="2">Please correct the following errors.</th></tr>
				<?php elseif ($saved): ?>
				<tr><td><em>Your photo has been saved to <?= $photo_path ?>.</em></td></tr>
				<?php elseif ($saved === false): ?>
				<tr><th class="phorm_error" colspan="2"><?= $file_error ?></th></tr>
				<?php endif ?>
			</thead>
			<tbody>
				<?= $form ?>
				<tr><th colspan="2"><input type="submit" value="Submit" /></th></tr>
			</tbody>
		</table>
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
