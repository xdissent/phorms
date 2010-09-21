<?php
error_reporting( E_ALL);

require_once('../phorms.php');

class CommentForm extends Phorm_Phorm
{

	protected function define_fields()
	{
		// Define form fields
		$this->post_id = new Phorm_Field_Hidden(array( 'required' ));
		$this->first_name = new Phorm_Field_Text("First name", 25, 255, array( 'required' ));
		$this->last_name = new Phorm_Field_Text("Last name", 25, 255, array( 'required' ));
		$this->email = new Phorm_Field_Email("Email address", 25, 255, array( 'required' ));
		$this->url = new Phorm_Field_URL("Home page", 25, 255);
		$this->number = new Phorm_Field_Integer("Favorite number", 20, 7, array( 'required' ));
		$this->message = new Phorm_Field_Textarea('Message', 5, 40, array( 'required' ));
		$this->notify = new Phorm_Field_Checkbox('Reply notification');

		// Add some help text
		$this->notify->help_text('Email me when my comment receives a response.');
		$this->email->help_text('We will never give out your email address.');
	}

	public function report()
	{
		var_dump($this->cleaned_data());
	}

}

// Set up the form
$post_id = 42;
$form = new CommentForm('post', false, array( 'post_id' => $post_id, 'notify' => true ));

// Check form validity
$valid = $form->is_valid();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Comment Form example for Phorm</title>
		<link rel="stylesheet" href="assets/style.css" type="text/css" />
	</head>
	<body>
		<h1>Add a comment</h1>
		<?php echo $form->open(); ?>
		<?php if( $form->has_errors() ) { ?>
		<div class="phorm_error">Please correct the following errors.</div>
		<?php } ?>
		<?php echo $form->as_table(TRUE); ?>
		<div>
			<input type="button" value="Reset form" onclick="this.form.reset();" />
			<input type="submit" value="Submit" />
		</div>
		<?php echo $form->close(); ?>

		<h4>Raw POST data:</h4>
		<?php var_dump($_POST);?>
		<hr />
		<?php
		if( $form->bound && $valid )
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


