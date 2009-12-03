<?php

error_reporting(6143|2048);

/**
 * Include Phorms.
 */
require_once dirname(__FILE__) . '/../src/Phorms/init.php';

function required($value)
{
    if ($value == '' || is_null($value))
        throw new Phorms_Validation_Error('This field is required.');
}

class CommentForm extends Phorms_Forms_Form
{
    protected function defineFields()
    {
        // Define form fields
        $this->post_id = new Phorms_Fields_HiddenField(array('required'));
        
        $this->first_name = new Phorms_Fields_CharField(
            'First name',
            'Enter your first name.',
            25,
            255,
            array('required')
        );
        
        $this->last_name = new Phorms_Fields_CharField(
            'Last name',
            'Enter your last name.',
            25,
            255,
            array('required')
        );
        
        $this->email = new Phorms_Fields_EmailField(
            'Email address',
            'Enter your email address.',
            25,
            255,
            array('required')
        );

        $this->url = new Phorms_Fields_URLField(
            'Home page', 
            'Enter the URL of your homepage.'
        );
        
        $this->number = new Phorms_Fields_IntegerField(
            'Favorite number',
            'Enter your favorite number in integer form.',
            7, 
            7,
            array('required')
        );
  
        $this->message = new Phorms_Fields_TextField(
            'Message',
            'Enter the message.',
            5,
            40,
            array('required')
        );
        
        $this->source = new Phorms_Fields_ChoiceField(
            'How you found us',
            'Choose how you found this site.',
            array(
                'search' => 'A search engine',
                'mouth' => 'Word of mouth',
                'ad' => 'An advertisement',
                'other' => 'Other'
            )
        );
        
        $this->weight = new Phorms_Fields_DecimalField(
            'Your exact weight',
            'Enter your weight as a decimal.',
            5,
            25,
            255,
            array('required')
        );

        $this->notify = new Phorms_Fields_BooleanField(
            'Reply notification', 
            'Check to receive a notification.'
        );

        // Add some help text
/*
        $this->notify->set_help_text('Email me when my comment receives a response.');
        $this->email->set_help_text('We will never give out your email address.');
        
*/
    }
    
    public function report()
    {
        var_dump($this->cleanedData());
    }
}

// Set up the form
$post_id = 42;
$form = new CommentForm(Phorms_Forms_Form::POST, false, array('post_id'=>$post_id, 'notify'=>true));

// Check form validity
$valid = $form->isValid();

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


