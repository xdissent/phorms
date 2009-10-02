<?php

error_reporting(6143|2048);

require_once('../src/phorms.php');

function required($value)
{
	if ($value == '' || is_null($value))
		throw new ValidationError('This field is required.');
}

class CommentForm extends Phorm
{
	protected function define_fields()
	{
		// Define form fields
		$this->post_id = new HiddenField(array('required'));
		$this->first_name = new TextField("First name", 25, 255, array('required'));
		$this->last_name = new TextField("Last name", 25, 255, array('required'));
		$this->email = new EmailField("Email address", 25, 255, array('required'));
		$this->url = new URLField("Home page", 25, 255);
		$this->number = new IntegerField("Favorite number", 7, array('required'));
		$this->message = new LargeTextField('Message', 5, 40, array('required'));
		$this->notify = new BooleanField('Reply notification');
		
		// Add some help text
		$this->notify->set_help_text('Email me when my comment receives a response.');
		$this->email->set_help_text('We will never give out your email address.');
	}
	
	public function report()
	{
		var_dump( $this->cleaned_data() );
	}
}

// Set up the form
$post_id = 42;
$form = new CommentForm(Phorm::POST, false, array('post_id'=>$post_id, 'notify'=>true));

// Check form validity
$valid = $form->is_valid();

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
					<tr><th colspan="2">Add a comment</th></tr>
					<?php if ( $form->has_errors() ): ?>
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
	
		<?php if ($form->is_bound() && $valid): ?>
			<h4>Processed and cleaned form data:</h4>
			<? $form->report() ?>
		<?php elseif ($form->has_errors()): ?>
			<h4>Errors:</h4>
			<?php var_dump($form->get_errors()); ?>
		<?php else: ?>
			<p><em>The form is unbound.</em></p>
		<?php endif ?>
	</body>
</html>


