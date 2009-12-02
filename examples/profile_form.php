<?php

error_reporting(6143|2048);

require_once('../src/Phorms/init.php');

function required($value)
{
	if ($value == '' || is_null($value))
		throw new Phorms_Validation_Error('This field is required.');
}

class ProfileForm extends Phorms_Forms_FieldsetForm
{
	protected function defineFields()
	{
		// Define form fields
		$this->user_id = new Phorms_Fields_HiddenField(array('required'));
		$this->first_name = new Phorms_Fields_CharField('First name', 'Enter your first name', 25, 255, array('required'));
		
/*
		$this->last_name = new TextField('Last name', 25, 255, array('required'));
		$this->email = new EmailField('Email address', 25, 255, array('required'));
		$this->url = new URLField('Home page', 25, 255);
		$this->bio = new LargeTextField('Bio', 5, 40, array('required'));
		$this->display = new MultipleChoiceField(
		  'Display',
		  array(
		      'first_name' => 'First Name',
		      'last_name' => 'Last Name',
		      'email' => 'Email Address',
		      'gender' => 'Gender',
		      'dob' => 'Date of Birth',
		      'address' => 'Address',
		      'city' => 'City',
		      'state' => 'State'
		  ),
		  array('required')
        );
		
		// Add some help text
		$this->email->set_help_text();
*/
	}
	
	protected function defineFieldsets()
	{
        $this->fieldsets = array(
            new Phorms_Fieldsets_Fieldset(
                'name',
                'Name',
                array('user_id', 'first_name')
            )
            /*
            new Phorms_Fieldsets_Fieldset('extra', 'Extra', array('email', 'url', 'bio')),
            new Phorms_Fieldsets_Fieldset('display', 'Display', array('display'))
            */
        );
	}
	
	public function report()
	{
		var_dump( $this->cleanedData() );
	}
}

// Set up the form
$post_id = 42;
$form = new ProfileForm(Phorms_Forms_Form::POST, false, array('user_id'=> '123', 'post_id'=>$post_id));

// Check form validity
$valid = $form->isValid();

?>
<html>
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
					<tr><th colspan="2">Add a comment</th></tr>
					<?php if ( $form->hasErrors() ): ?>
					<tr><th class="phorm_error" colspan="2">Please correct the following errors.</th></tr>
					<?php endif ?>
				</thead>
				<tbody>
					<?= $form ?>
					<tr>
						<th colspan="2">
							<input type="button" value="Clear form" onclick="javascript:location.href='<?= $_SERVER['PHP_SELF'] ?>'" />
							<input type="submit" value="Submit" />
						</th>
					</tr>
				</tbody>
			</table>
		<?= $form->close() ?>
		
		<h4>Raw POST data:</h4>
		<?php var_dump($_POST); ?>
	
		<hr />
	
		<?php if ($form->isBound() && $valid): ?>
			<h4>Processed and cleaned form data:</h4>
			<? $form->report() ?>
		<?php elseif ($form->hasErrors()): ?>
			<h4>Errors:</h4>
			<?php var_dump($form->getErrors()); ?>
		<?php else: ?>
			<p><em>The form is unbound.</em></p>
		<?php endif ?>
	</body>
</html>