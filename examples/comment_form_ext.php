<?php

error_reporting(6143|2048);

require_once('../src/phorms.php');
require_once('../src/phorms_ext.php');

function required($value)
{
	if ($value == '' || is_null($value))
		throw new ValidationError('This field is required.');
}

class CommentForm extends PhormExt
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
		$this->date = new DateTimeField('Date', array('required'));
		
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
	<head>
		<script src="../src/javascript/scriptaculous/lib/prototype.js" type="text/javascript"></script>
		<script src="../src/javascript/validation.js" type="text/javascript"></script>
	</head>
	<body>
		<style>
			.phorm_error { color: #bb0000; font-size: 10pt; text-align: left; font-style: oblique; }
			.phorm_help { margin: 0; padding: 2px; font-size: 10pt; font-style: oblique; color: #666; }
			
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

			form .form_label_nostyle
			{
				background: none;
			}

			/* Input */
			form input, form select
			{
				margin-left: 1%;
				width: 58%;
				border: #CCC 1px solid;
			}

			form .form_input_day_month
			{
				width: 3%;
			}

			form .form_input_year
			{
				width: 6%;
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
			
			/* Validation */
			.validation-advice {
				margin: 5px 0;
				padding: 5px;
				background-color: #FF3300;
				color : #FFF;
				font-weight: bold;
			}

		</style>

		<?= $form->open() ?>
		<h2>Add a comment</h2>
		<?php if ( $form->has_errors() ): ?>
		<p class="phorm_error">Please correct the following errors.</p>
		<?php endif ?>
		<?= $form ?>
			<p>
				<input type="reset" value="Clear form" />
				<input type="submit" value="Submit" />
			</p>
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
		<script type="text/javascript">
			new Validation(document.forms[0], {immediate : true}); // OR new Validation('form-id');
		</script>
	</body>
</html>


