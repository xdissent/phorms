<?php

/**
 * Fields
 *
 * Field classes that are used to compose a Phorm instance.
 * 
 * @author Jeff Ober
 * @package Fields
 * @see Phorm,Widget
 **/

/**
 * Widget classes used to serialize form elements.
 **/
require_once(PHORMS_ROOT . 'widgets.php');
/**
 * Various helper types.
 **/
require_once(PHORMS_ROOT . 'types.php');

/**
 * ValidationError
 * 
 * Thrown when a field's data fails to validate.
 * @author Jeff Ober
 * @package Fields
 **/
class ValidationError extends Exception { }

/**
 * PhormField
 * 
 * Abstract class from which all other field classes are derived.
 * @author Jeff Ober
 * @package Fields
 **/
abstract class PhormField
{
    /**
     * The field's text label.
     **/
    private $label;
    /**
     * Store's the field's value. Set during validation.
     **/
    private $value;
    /**
     * Array of callbacks used to validate field data. May be either a string
     * denoting a function or an array of array(instance, string method) to use
     * a class instance method.
     **/
    private $validators;
    /**
     * Associative array of key/value pairs representing HTML attributes of the field.
     **/
    private $attributes;
    /**
     * Array storing errors generated during field validation.
     **/
    private $errors;
    /**
     * Storage of the "cleaned" field value.
     **/
    private $imported;
    /**
     * Help text for the field. This is printed out with the field HTML.
     **/
    private $help_text = "";
    /**
     * If true, this field uses multiple field widgets.
     * @see widgets.php
     **/
    public $multi_field = false;
    /**
     * Stores the result of field validation to prevents double-validation.
     **/
    private $valid;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's label
     * @param array $validators callbacks used to validate field data
     * @param array $attributes an assoc of key/value pairs representing HTML attributes
     * @return null
     **/
    public function __construct($label, array $validators=array(), array $attributes=array())
    {
        $this->label = (string)$label;
        $this->attributes = $attributes;
        $this->validators = $validators;
    }
    
    /**
     * Assigns help text to the field.
     * @author Jeff Ober
     * @param string $text the help text
     * @return null
     **/
    public function set_help_text($text)
    {
        $this->help_text = $text;
    }
    
    /**
     * Sets the value of the field.
     * @author Jeff Ober
     * @param mixed $value the field's value
     * @return null
     **/
    public function set_value($value)
    {
        $this->value = $value;
    }
    
    /**
     * Returns the "cleaned" value of the field.
     * @author Jeff Ober
     * @return mixed the field's "cleaned" value
     **/
    public function get_value()
    {
        return $this->imported;
    }
    
    /**
     * Sets an HTML attribute of the field.
     * @author Jeff Ober
     * @param string $key the attribute name
     * @param string $value the attribute's value
     * @return null
     **/
    public function set_attribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Returns the value of an HTML attribute or null if not set.
     * @author Jeff Ober
     * @param string $key the attribute name to look up
     * @return string|null the attribute's value or null if not set
     **/
    public function get_attribute($key)
    {
        if (array_key_exists($key, $this->attributes))
            return $this->attributes[$key];
        return null;
    }
    
    /**
     * Returns a list of errors generated during validation. If the field is not
     * yet validated, returns null.
     * @author Jeff Ober
     * @return array|null
     **/
    public function get_errors()
    {
        return $this->errors;
    }
    
    /**
     * Returns an HTML string containing the field's help text.
     * @author Jeff Ober
     * @return string the HTML help text paragraph
     **/
    public function help_text()
    {
        return sprintf('<p class="phorm_help">%s</p>', htmlentities($this->help_text));
    }
    
    /**
     * Returns the HTML field label.
     * @author Jeff Ober
     * @return string the HTML label tag
     **/
    public function label()
    {
        return sprintf('<label for="%s">%s</label>', (string)$this->get_attribute('id'), $this->label);
    }
    
    /**
     * Returns the field's tag as HTML.
     * @author Jeff Ober
     * @return string the field as HTML
     **/
    public function html()
    {
        $widget = $this->get_widget();
        $attr = $this->attributes;
        return $widget->html($this->value, $this->attributes);
    }
    
    /**
     * Returns the field's errors as an unordered list with the class "phorm_error".
     * @author Jeff Ober
     * @return string the field errors as an unordered list
     **/
    public function errors()
    {
        $elts = array();
        if (is_array($this->errors) && count($this->errors) > 0)
            foreach ($this->errors as $error)
                $elts[] = sprintf('<li>%s</li>', $error);
        return sprintf('<ul class="phorm_error">%s</ul>', implode($elts));
    }
    
    /**
     * Serializes the field to HTML.
     * @author Jeff Ober
     * @return string the field's complete HTMl representation.
     **/
    public function __toString()
    {
        return $this->html() . $this->help_text() . $this->errors();
    }
    
    /**
     * On the first call, calls each validator on the field value, and returns
     * true if each returned successfully, false if any raised a
     * ValidationError. On subsequent calls, returns the same value as the
     * initial call. If $reprocess is set to true (default: false), will
     * call each of the validators again. Stores the "cleaned" value of the
     * field on success.
     * @author Jeff Ober
     * @param boolean $reprocess if true, ignores memoized result of initial call
     * @return boolean true if the field's value is valid
     * @see PhormField::$valid,PhormField::$imported,PhormField::$validators,PhormField::$errors
     **/
    public function is_valid($reprocess=false)
    {
        if ( $reprocess || is_null($this->valid) )
        {
            // Pre-process value
            $value = $this->prepare_value($this->value);

            $this->errors = array();
            $v = $this->validators;

            foreach($v as $f)
            {
                try { call_user_func($f, $value); }
                catch (ValidationError $e) { $this->errors[] = $e->getMessage(); }
            }
            
            if ( $value !== '' )
            {
                try { $this->validate($value); }
                catch (ValidationError $e) { $this->errors[] = $e->getMessage(); }
            }

            if ( $this->valid = ( count($this->errors) === 0 ) )
                $this->imported = $this->import_value($value);
        }
        return $this->valid;
    }
    
    /**
     * Pre-processes a value for validation, handling magic quotes if used.
     * @author Jeff Ober
     * @param string $value the value from the form array
     * @return string the pre-processed value
     **/
    protected function prepare_value($value)
    {
        return ( get_magic_quotes_gpc() ) ? stripslashes($value) : $value;
    }
    
    /**
     * Defined in derived classes; must return an instance of PhormWidget.
     * @return PhormWidget the field's widget
     * @see PhormWidget
     **/
    abstract protected function get_widget();
    
    /**
     * Raises a ValidationError if $value is invalid.
     * @param string|mixed $value (may be mixed if prepare_value returns a non-string)
     * @throws ValidationError
     * @return null
     * @see ValidationError
     **/
    abstract protected function validate($value);
    
    /**
     * Returns the field's "imported" value, if any processing is required. For
     * example, this function may be used to convert a date/time field's string
     * into a unix timestamp or a numeric string into an integer or float.
     * @param string|mixed $value the pre-processed string value (or mixed if prepare_value returns a non-string)
     * @return mixed
     **/
    abstract public function import_value($value);
}

/**
 * FileField
 * 
 * A field representing a file upload input.
 * @author Jeff Ober
 * @package Fields
 * @see File
 **/
class FileField extends PhormField
{
    /**
     * Stores the valid types for this field.
     **/
    private $types;
    /**
     * Stores the maximum size boundary in bytes.
     **/
    private $max_size;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's string label
     * @param array $mime_types a list of valid mime types
     * @param int $max_size the maximum upload size in bytes
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, array $mime_types, $max_size, array $validators=array(), array $attributes=array())
    {
        $this->types = $mime_types;
        $this->max_size = $max_size;
        parent::__construct($label, $validators, $attributes);
    }
    
    /**
     * Returns true if the file was uploaded without an error.
     * @author Jeff Ober
     * @return boolean
     **/
    protected function file_was_uploaded()
    {
        $file = $this->get_file_data();
        return !$file['error'];
    }
    
    /**
     * Returns an error message for a file upload error code.
     * @author Jeff Ober
     * @param int $errno the error code (from $_FILES['name']['error'])
     * @return string the error message
     **/
    protected function file_upload_error($errno)
    {
        switch ($errno)
        {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
            return "The file sent was too large.";
            
            case UPLOAD_ERR_PARTIAL:
            return "There was an error uploading the file; please try again.";
            
            case UPLOAD_ERR_NO_FILE:
            return "The file was not sent; please try again.";
            
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
            return "There was a system error during upload; please contact the webmaster (error number {$errno}).";
            
            case UPLOAD_ERR_OK:
            default:
            return false;
        }
    }
    
    /**
     * Returns a FileWidget.
     * @author Jeff Ober
     * @return FileWidget
     * @see FileWidget,FileField::$types
     **/
    protected function get_widget()
    {
        return new FileWidget($this->types);
    }
    
    /**
     * Returns an array of file upload data.
     * @author Jeff Ober
     * @return array file upload data
     **/
    protected function get_file_data()
    {
        $data = $_FILES[ $this->get_attribute('name') ];
        $data['error'] = $this->file_upload_error($data['error']);
        return $data;
    }
    
    /**
     * Returns a new File instance for this field's data.
     * @author Jeff Ober
     * @return File a new File instance
     * @see File
     **/
    protected function get_file()
    {
        return new File( $this->get_file_data() );
    }
    
    /**
     * On a successful upload, returns a new File instance.
     * @author Jeff Ober
     * @param array $value the file data from $_FILES
     * @return File a new File instance
     * @see File
     **/
    public function import_value($value)
    {
        if ( $this->file_was_uploaded() )
            return $this->get_file();
    }
    
    /**
     * Returns the file's $_FILES data array or false if the file was not
     * uploaded.
     * @author Jeff Ober
     * @param mixed $value
     * @return boolean|File
     **/
    public function prepare_value($value)
    {
        if ( $this->file_was_uploaded() )
            return $this->get_file();
        else
            return false;
    }
    
    /**
     * Throws a ValidationError if the file upload resulted in an error, if
     * the file was not a valid type, or if the file exceded the maximum size.
     * @author Jeff Ober
     * @param mixed $value
     * @return null
     * @throws ValidationError
     **/
    protected function validate($value)
    {
        $file = $this->get_file_data();
        
        if ($file['error'])
            throw new ValidationError($file['error']);
        
        if (is_array($this->types) && !in_array($file['type'], $this->types))
            throw new ValidationError("Files of type ${file['type']} are not accepted.");
        
        if ($file['size'] > $this->max_size)
            throw new ValidationError(sprintf("Files are limited to %s bytes.", number_format($this->max_size)));
    }
}

/**
 * ImageField
 * 
 * A FileField that is pre-configured for images. Valid types are PNG, GIF, and
 * JPG. Returns an Image instance instead of a File instance. Identical to the
 * FileField in all other ways.
 * @author Jeff Ober
 * @package Fields
 * @see FileField,Image
 **/
class ImageField extends FileField
{
    /**
     * @author Jeff Ober
     * @param string $label the field's string label
     * @param int $max_size the maximum upload size in bytes
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $max_size, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, array('image/png', 'image/gif', 'image/jpg', 'image/jpeg'), $max_size, $validators, $attributes);
    }
    
    /**
     * Returns a new Image.
     * @author Jeff Ober
     * @return Image
     **/
    protected function get_file()
    {
        return new Image( $this->get_file_data() );
    }
}

/**
 * TextField
 * 
 * A simple text field.
 * @author Jeff Ober
 * @package Fields
 **/
class TextField extends PhormField
{
    /**
     * Stores the maximum value length in characters.
     **/
    private $max_length;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param int $size the field's size attribute
     * @param int $max_length the maximum size in characters
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $size, $max_length, array $validators=array(), array $attributes=array())
    {
        $this->max_length = $max_length;
        $attributes['size'] = $size;
        parent::__construct($label, $validators, $attributes);
    }
    
    /**
     * Returns a new CharWidget.
     * @author Jeff Ober
     * @return CharWidget
     **/
    protected function get_widget()
    {
        return new CharWidget();
    }
    
    /**
     * Validates that the value is less than $this->max_length;
     * @author Jeff Ober
     * @return null
     * @throws ValidationError
     * @see TextField::$max_width
     **/
    protected function validate($value)
    {
        if (strlen($value) > $this->max_length)
            throw new ValidationError('Must be fewer than {$this->max_length} characters in length.');
    }
    
    /**
     * Imports the value by decoding HTML entities.
     * @author Jeff Ober
     * @param string $value
     * @return string the decoded value
     **/
    public function import_value($value)
    {
        return html_entity_decode((string)$value);
    }
}

/**
 * HiddenField
 * 
 * A hidden text field that does not print a label.
 * @author Jeff Ober
 * @package Fields
 **/
class HiddenField extends TextField
{
    /**
     * @author Jeff Ober
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct(array $validators=array(), array $attributes=array())
    {
        parent::__construct('', 255, $validators, $attributes);
    }
    
    /**
     * Does not print out a label.
     * @author Jeff Ober
     * @return string an empty string
     **/
    public function label()
    {
        return '';
    }
    
    /**
     * Does not print out the help text.
     * @author Jeff Ober
     * @return string an empty string.
     **/
    public function help_text()
    {
        return '';
    }
    
    /**
     * Returns a new HiddenWidget.
     * @author Jeff Ober
     * @return HiddenWidget
     **/
    protected function get_widget()
    {
        return new HiddenWidget();
    }
}

/**
 * PasswordField
 * 
 * A password field that uses a user-specified hash function to import values.
 * @author Jeff Ober
 * @package Fields
 **/
class PasswordField extends TextField
{
    /**
     * The hash function to encode the user-submitted value.
     **/
    private $hash_function;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param int $size the field's size attribute
     * @param int $max_length the maximum size in characters
     * @param callback $hash_function a (string) function or array (instance, string method) callback
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $size, $max_length, $hash_function, array $validators=array(), array $attributes=array())
    {
        $this->max_length = $max_length;
        $this->hash_function = $hash_function;
        $attributes['size'] = $size;
        parent::__construct($label, $validators, $attributes);
    }
    
    /**
     * Returns a PasswordWidget.
     * @author Jeff Ober
     * @return PasswordWidget
     **/
    public function get_widget()
    {
        return new PasswordWidget();
    }
    
    /**
     * Returns a hash-encoded value.
     * @author Jeff Ober
     * @param string $value
     * @return string the encoded value
     **/
    public function import_value($value)
    {
        return call_user_func($this->hash_function, array($value));
    }
}

/**
 * LargeTextField
 * 
 * A large text field using a textarea tag.
 * @author Jeff Ober
 * @package Fields
 **/
class LargeTextField extends PhormField
{
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param int $rows the number of rows
     * @param int $cols the number of columns
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $rows, $cols, array $validators=array(), array $attributes=array())
    {
        $attributes['cols'] = $cols;
        $attributes['rows'] = $rows;
        parent::__construct($label, $validators, $attributes);
    }
    
    /**
     * Returns a new TextWidget.
     * @author Jeff Ober
     * @return TextWidget
     **/
    protected function get_widget()
    {
        return new TextWidget();
    }
    
    /**
     * Returns null.
     * @author Jeff Ober
     * @return null
     **/
    protected function validate($value)
    {
        return true;
    }
    
    /**
     * Imports the value by decoding HTML entities.
     * @author Jeff Ober
     * @param string $value
     * @return string the decoded value
     **/
    public function import_value($value)
    {
        return html_entity_decode((string)$value);
    }
}

/**
 * IntegerField
 * 
 * A field that accepts only integers.
 * @author Jeff Ober
 * @package Fields
 **/
class IntegerField extends PhormField
{
    /**
     * Stores the max number of digits permitted.
     **/
    private $max_digits;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param int $max_digits the maximum number of digits permitted
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $max_digits, array $validators=array(), array $attributes=array())
    {
        $attributes['size'] = 20;
        parent::__construct($label, $validators, $attributes);
        $this->max_digits = $max_digits;
    }
    
    /**
     * Returns a new CharWidget.
     * @author Jeff Ober
     * @return CharWidget
     **/
    public function get_widget()
    {
        return new CharWidget();
    }
    
    /**
     * Validates that the value is parsable as an integer and that it is fewer
     * than $this->max_digits digits.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        if (preg_match('/\D/', $value) || strlen((string)$value) > $this->max_digits)
            throw new ValidationError("Must be a number with fewer than {$this->max_digits} digits.");
    }
    
    /**
     * Parses the value as an integer.
     * @author Jeff Ober
     * @param string $value
     * @return int
     **/
    public function import_value($value)
    {
        return (int)(html_entity_decode((string)$value));
    }
}

/**
 * DecimalField
 * 
 * A field that accepts only decimals of a specified precision.
 * @author Jeff Ober
 * @package Fields
 **/
class DecimalField extends PhormField
{
    /**
     * The maximum precision of the field's value.
     **/
    private $precision;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param int $precision the maximum number of decimals permitted
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $precision, array $validators=array(), array $attributes=array())
    {
        $attributes['size'] = 20;
        parent::__construct($label, $validators, $attributes);
        $this->precision = $precision;
    }
    
    /**
     * Returns a new CharWidget.
     * @author Jeff Ober
     * @return CharWidget
     **/
    public function get_widget()
    {
        return new CharWidget();
    }
    
    /**
     * Validates that the value is parsable as a float.
     * @author Jeff Ober
     * @param string value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        if (!is_numeric($value))
            throw new ValidationError("Invalid decimal value.");
    }
    
    /**
     * Returns the parsed float, rounded to $this->precision digits.
     * @author Jeff Ober
     * @param string $value
     * @return float the parsed value
     **/
    public function import_value($value)
    {
        return round((float)(html_entity_decode($value)), $this->precision);
    }
}

/**
 * BooleanField
 * 
 * A field representing a boolean choice using a checkbox field.
 * @author Jeff Ober
 * @package Fields
 **/
class BooleanField extends PhormField
{
    /**
     * True when the field is checked (true).
     **/
    private $checked;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, $validators, $attributes);
        parent::set_value('on');
        $this->checked = false;
    }
    
    /**
     * Sets the value of the field.
     * @author Jeff Ober
     * @param boolean $value
     * @return null
     **/
    public function set_value($value)
    {
        $this->checked = (boolean)$value;
    }
    
    /**
     * Returns true if the field is checked.
     * @author Jeff Ober
     * @return boolean
     **/
    public function get_value()
    {
        return $this->checked;
    }
    
    /**
     * Returns a new CheckboxWidget.
     * @author Jeff Ober
     * @return CheckboxWidget
     **/
    public function get_widget()
    {
        return new CheckboxWidget($this->checked);
    }
    
    /**
     * Returns null.
     * @author Jeff Ober
     * @return null
     **/
    public function validate($value)
    {
        return null;
    }
    
    /**
     * Returns true if the field was checked in the user-submitted data, false
     * otherwise.
     * @author Jeff Ober
     * @return boolean
     **/
    public function import_value($value)
    {
        return $this->checked;
    }
    
    /**
     * Returns the value.
     * @author Jeff Ober
     * @param string $value
     * @param string
     **/
    public function prepare_value($value)
    {
        return $value;
    }
}

/**
 * DropDownField
 * 
 * A field that presents a list of options as a drop-down.
 * @author Jeff Ober
 * @package Fields
 **/
class DropDownField extends PhormField
{
    /**
     * An array storing the drop-down's choices.
     **/
    private $choices;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param array $choices a list of choices as actual_value=>display_value
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, array $choices, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, $validators, $attributes);
        $this->choices = $choices;
    }
    
    /**
     * Returns a new SelectWidget.
     * @author Jeff Ober
     * @return SelectWidget
     **/
    public function get_widget()
    {
        return new SelectWidget($this->choices);
    }
    
    /**
     * Validates that $value is present in $this->choices.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     * @see DropDownField::$choices
     **/
    public function validate($value)
    {
        if (!in_array($value, array_keys($this->choices)))
            throw new ValidationError("Invalid selection.");
    }
    
    /**
     * Imports the value by decoding any HTML entities. Returns the "actual"
     * value of the option selected.
     * @author Jeff Ober
     * @param string $value
     * @return string the decoded string
     **/
    public function import_value($value)
    {
        return html_entity_decode((string)$value);
    }
}

/**
 * URLField
 * 
 * A text field that only accepts a reasonably-formatted URL. Supports HTTP(S)
 * and FTP. If a value is missing the HTTP(S)/FTP prefix, adds it to the final
 * value.
 * @author Jeff Ober
 * @package Fields
 **/
class URLField extends TextField
{
    /**
     * Prepares the value by inserting http:// to the beginning if missing.
     * @author Jeff Ober
     * @param string $value
     * @return string
     **/
    public function prepare_value($value)
    {
        if (!preg_match('@^(http|ftp)s?://@', $value))
            return sprintf('http://%s', $value);
        else
            return $value;
    }
    
    /**
     * Validates the the value is a valid URL (mostly).
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        parent::validate($value);
        if ( !preg_match('@^(http|ftp)s?://(\w+(:\w+)?\@)?(([-_\.a-zA-Z0-9]+)\.)+[-_\.a-zA-Z0-9]+(\w*)@', $value) )
            throw new ValidationError("Invalid URL.");
    }
}

/**
 * EmailField
 * 
 * A text field that only accepts a valid email address.
 * @author Jeff Ober
 * @package Fields
 **/
class EmailField extends TextField
{
    /**
     * Validates that the value is a valid email address.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        parent::validate($value);
        if ( !preg_match('@^([-_\.a-zA-Z0-9]+)\@(([-_\.a-zA-Z0-9]+)\.)+[-_\.a-zA-Z0-9]+$@', $value) )
            throw new ValidationError("Invalid email address.");
    }
}

/**
 * DateTimeField
 * 
 * A text field that accepts a variety of date/time formats (those accepted by
 * PHP's built-in strtotime.) Note that due to the reliance on strtotime, this
 * class has a serious memory leak in PHP 5.2.8 (I am unsure if it is present
 * as well in 5.2.9+.)
 * @author Jeff Ober
 * @package Fields
 **/
class DateTimeField extends TextField
{
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, 25, 100, $validators, $attributes);
    }
    
    /**
     * Validates that the value is parsable as a date/time value.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        parent::validate($value);
        if (!strtotime($value))
            throw new ValidationError("Date/time format not recognized.");
    }
    
    /**
     * Imports the value and returns a unix timestamp (the number of seconds
     * since the epoch.)
     * @author Jeff Ober
     * @param string $value
     * @return int the date/time as a unix timestamp
     **/
    public function import_value($value)
    {
        $value = parent::import_value($value);
        return strtotime($value);
    }
}

/**
 * RegexField
 * 
 * A text field that validates using a regular expression and imports to an
 * array of captured values.
 * @author Jeff Ober
 * @package Fields
 **/
class RegexField extends TextField
{
    /**
     * The (pcre) regular expression.
     **/
    private $regex;
    /**
     * The error message thrown when unmatched.
     **/
    private $message;
    /**
     * Storage for matches during validation so that the expression needn't run twice.
     **/
    private $matches;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param string $regex the (pcre) regex used to validate and parse the field
     * @param string $error_msg the message thrown on a regex mismatch
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $regex, $error_msg, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, 25, 100, $validators, $attributes);
        $this->regex = $regex;
        $this->message = $error_msg;
    }
    
    /**
     * Validates that the value matches the regular expression.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        parent::validate($value);
        if (!preg_match($this->regex, $value, $this->matches))
            throw new ValidationError($this->message);
    }
    
    /**
     * Returns the captured values that were parsed inside validate().
     * @author Jeff Ober
     * @param string $value
     * @return array the captured matches
     **/
    public function import_value($value)
    {
        return $this->matches;
    }
}

/**
 * ScanField
 * 
 * Akin to the RegexField, but instead using sscanf() for more rigid matching
 * and type-cast values.
 * @author Jeff Ober
 * @package Fields
 * @see RegexField
 **/
class ScanField extends TextField
{
    /**
     * The sscanf() format.
     **/
    private $format;
    /**
     * The error message on match failure.
     **/
    private $message;
    /**
     * Storage for the matched values to prevent calling sscanf twice.
     **/
    private $matched;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param string $format the sscanf format used to validate and parse the field
     * @param string $error_msg the message thrown on a mismatch
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, $format, $error_msg, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, 25, 100, $validators, $attributes);
        $this->format = $format;
        $this->message = $error_msg;
    }
    
    /**
     * Validates that the value matches the sscanf format.
     * @author Jeff Ober
     * @param string $value
     * @return null
     * @throws ValidationError
     **/
    public function validate($value)
    {
        parent::validate($value);
        $this->matched = sscanf($value, $this->format);
        if ( count($this->matched) === 0 )
            throw new ValidationError($this->message);
    }
    
    /**
     * Returns the parsed matches that were captured in validate().
     * @param string $value
     * @return array the captured values
     **/
    public function import_value($value)
    {
        return $this->matched;
    }
}

/**
 * MultipleChoiceField
 * 
 * A compound field offering multiple choices as a select multiple tag.
 * @author Jeff Ober
 * @package Fields
 **/
class MultipleChoiceField extends PhormField
{
    /**
     * Specifies that this field's name attribute must be post-fixed by [].
     **/
    public $multi_field = true;
    /**
     * Stores the field options as actual_value=>display_value.
     **/
    private $choices;
    
    /**
     * @author Jeff Ober
     * @param string $label the field's text label
     * @param array $choices a list of choices as actual_value=>display_value
     * @param array $validators a list of callbacks to validate the field data
     * @param array $attributes a list of key/value pairs representing HTML attributes
     **/
    public function __construct($label, array $choices, array $validators=array(), array $attributes=array())
    {
        parent::__construct($label, $validators, $attributes);
        $this->choices = $choices;
    }
    
    /**
     * Returns a new MultiSelectWidget.
     * @author Jeff Ober
     * @return MultiSelectWidget
     **/
    public function get_widget()
    {
        return new MultiSelectWidget($this->choices);
    }
    
    /**
     * Validates that each of the selected choice exists in $this->choices.
     * @author Jeff Ober
     * @param array $value
     * @return null
     * @throws ValidationError
     * @see MultipleChoiceField::$choices
     **/
    public function validate($value)
    {
        if (!is_array($value))
            throw new ValidationError('Invalid selection');
        
        foreach ($value as $v)
            if (!in_array($v, array_keys($this->choices)))
                throw new ValidationError("Invalid selection.");
    }
    
    /**
     * Imports the value as an array of the actual values (from $this->choices.)
     * @author Jeff Ober
     * @param array $value
     * @return array
     **/
    public function import_value($value)
    {
        if (is_array($value))
            foreach ($value as $key => &$val)
                $val = html_entity_decode($val);
        return $value;
    }
}

/**
 * OptionsField
 * 
 * A selection of choices represented as a series of labeled checkboxes.
 * @author Jeff Ober
 * @package Fields
 **/
class OptionsField extends MultipleChoiceField
{
    /**
     * Returns a new OptionGroupWidget.
     * @author Jeff Ober
     * @return OptionGroupWidget
     **/
    public function get_widget()
    {
        return new OptionGroupWidget($this->options);
    }
}

?>