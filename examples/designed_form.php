<?php
error_reporting( E_ALL);
require_once('../phorms.php');

define('CONFIRMATION_EMAIL_TO', ''); //enter your email address here to have this example send an email (assuming your server has php mail capabilities)

//Form field definitions:
class OrderForm extends Phorm_Phorm {
	protected function define_fields() {
		$this->name = new Phorm_Field_Text("Name", 24, 255, array('required'));
		$this->company = new Phorm_Field_Text("Company name", 24, 255);
		$this->street = new Phorm_Field_Text("Street or P.O.", 24, 255, array('required'));
		$this->apt = new Phorm_Field_Text("Apt #", 5, 10);
		$this->city = new Phorm_Field_Text("City", 14, 255, array('required'));
		$this->state = new Phorm_Field_Text("State/province", 3, 4, array('required'));
		$this->postal = new Phorm_Field_Text("ZIP or postal code", 10, 10, array('required'));
		$this->country = new Phorm_Field_Text("Country", 24, 40);
		$this->phone = new Phorm_Field_Text("Phone number", 22, 24);
		$this->fax = new Phorm_Field_Text("Fax number", 22, 24);
		$this->email = new Phorm_Field_Email("Email address", 24, 255, array('required'));
		$this->url = new Phorm_Field_URL("Web page URL", 40, 255);
		$this->bookname = new Phorm_Field_DropDown("Selected Item", array(
			'item1' => 'Book',
			'item2' => 'Chair',
			'item3' => 'Table',
			'item4' => 'Lamp',
		), array('required'));
		$this->quantity = new Phorm_Field_Integer("Quantity", 5, 5, array('required'));
		$this->message = new Phorm_Field_Textarea("Message", 8, 40);
	}
}

//Form validation/processing:
$form = new OrderForm();

if ($form->is_valid()) {
	$email_body = '';
	foreach ($form->fields() as $field) {
		$email_body .= $field->label(false) . ': ' . $field->get_value() . "\n";
	}
	
	if (CONFIRMATION_EMAIL_TO) {
		mail(CONFIRMATION_EMAIL_TO, 'New Order has been received!', $email_body);
		echo 'Thank you for your order!';
	} else {
		echo nl2br($email_body);
	}
	exit;
}

///////////////////////////////////////////////////////////////////////////////

//Display page and form:
?>
<html>
<head>
	<title>Form test</title>
	
	<style>
	.errors {
		background-color: #FFCCCC;
		border: 1px solid red;
		padding: 5px;
		color: black;
	}
	.errors ul {
		padding-top: 5px;
		font-weight: bold;
		list-style: disc inside;
	
	}
	</style>
</head>
<body>
<div id="rtCont">		
	<table width="100%" height="324"  border="0" cellpadding="6" cellspacing="0" class="redborder  maintable">
		<tr>
			<td>
				<h2>Order Form</h2>
			</td>
		</tr>
		
		<?php if ($form->has_errors()): ?>
		<tr>
			<td>
				<div class="errors">
					<p>Could not submit your request due to the following errors:</p>
					<ul>
						<?php echo $form->display_errors('<li>', '</li>'); ?>
					</ul>
				</div>
			</td>
		</tr>
		<?php endif; ?>
		
		<tr>
			<td>
				<?php echo $form->open(); ?>
					<table width="122" border="0" cellspacing="0" cellpadding="5">
						<tr>
							<td align="right" nowrap><b><?php echo $form->name->label(); ?></b></td>
							<td><?php echo $form->name->html(); ?></td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->company->label(); ?></b></td>
							<td><?php echo $form->company->html(); ?></td>
						</tr>
						<tr>
							<td align="right" nowrap><b><u>Address</u></b></td>
							<td></td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->company->label(); ?></b></td>
							<td nowrap>
								<?php echo $form->street->html(); ?>
								<b><?php echo $form->apt->label(); ?> <?php echo $form->apt->html(); ?></b>
							</td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->city->label(); ?></b></td>
							<td nowrap><?php echo $form->city->html(); ?></td>
						</tr>
	
						<tr>
							<td align="right" nowrap><b><?php echo $form->state->label(); ?></b></td>
							<td nowrap>
								<?php echo $form->state->html(); ?>
								<b><?php echo $form->postal->label(); ?></b> <?php echo $form->postal->html(); ?>
							</td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->country->label(); ?></b></td>
							<td><?php echo $form->country->html(); ?></td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->phone->label(); ?></b></td>
							<td nowrap>
								<?php echo $form->phone->html(); ?>
								<b><?php echo $form->fax->label(); ?> <?php echo $form->fax->html(); ?></b>
							</td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->email->label(); ?></b></td>
							<td><?php echo $form->email->html(); ?></td>
						</tr>
						<tr>
							<td align="right" nowrap><b><?php echo $form->url->label(); ?></b></td>
							<td nowrap>http://<?php echo $form->url->html(); ?></td>
						</tr>
	
						<tr>
							<td align="right" nowrap><b><?php echo $form->bookname->label(); ?></b></td>
							<td align="left">
								<?php echo $form->bookname->html(); ?>
							</td>
						</tr>
	
						<tr>
							<td align="right" nowrap><b><?php echo $form->quantity->label(); ?></b></td>
							<td nowrap><?php echo $form->quantity->html(); ?></td>
						</tr>
	
						<tr>
							<td style="padding-top:8px" align="right" valign="top" nowrap><b><?php echo $form->message->label(); ?></b></td>
							<td style="padding-top:8px" valign="top" nowrap><?php echo $form->message->html(); ?><br>
							 &nbsp;&nbsp;<input type="submit" value="Submit!">
						</tr>
					</table>
				<?php echo $form->close(); ?>
			</td>
		</tr>
	</table>
</div>

</body>
</html>
