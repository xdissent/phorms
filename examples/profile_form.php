<?php
error_reporting( E_ALL);

require_once('../phorms.php');

class ProfileForm extends Phorm_FieldsetPhorm
{
	protected function define_fields()
	{
		// Define form fields
		$this->user_id = new Phorm_Field_Hidden(array( 'required' ));
		$this->first_name = new Phorm_Field_Text("First name", 25, 255, array( 'required' ));
		$this->last_name = new Phorm_Field_Text("Last name", 25, 255, array( 'required' ));
		$this->email = new Phorm_Field_Email("Email address", 25, 255, array( 'required' ));
		$this->url = new Phorm_Field_URL("Home page", 25, 255);
		$this->bio = new Phorm_Field_Textarea('Bio', 5, 40, array( 'required' ));

		// Add some help text
		$this->email->help_text('We will never give out your email address.');
	}

	protected function define_fieldsets()
	{
		$this->fieldsets = array(
			new Phorm_Fieldset('name', 'Name', array( 'user_id', 'first_name', 'last_name' )),
			new Phorm_Fieldset('extra', 'Extra', array( 'email', 'url', 'bio' ))
		);
	}

	public function report()
	{
		var_dump($this->cleaned_data());
	}

}

// Set up the form
$post_id = 42;
$form = new ProfileForm('post', false, array( 'post_id' => $post_id ));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Profile Form example for Phorm</title>
		<link rel="stylesheet" href="assets/style.css" type="text/css" />
	</head>
	<body>
		<?php echo $form->open();?>
		<h1>Profile Data</h1>
		<?php if( $form->has_errors() ) { ?>
		<div class="phorm_error">Please correct the following errors.</div>
		<?php } ?>
		<?php echo $form->as_table(); ?>

		<div>
			<input type="button" value="Clear form" onclick="javascript:location.href='<?php echo $_SERVER['PHP_SELF']?>'" />
			<input type="submit" value="Submit" onclick="javascript:console.log(this.parent);"/>
		</div>
		<?php echo $form->close();?>
		<h4>Raw POST data:</h4>
		<?php var_dump($_POST);?>
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
