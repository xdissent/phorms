<?php

/**
 * Various types used by multiple packages.
 *
 * @author Jeff Ober
 * @package default
 * 
 **/

/**
 * File
 * 
 * Record class for FileField data.
 * 
 * @author Jeff Ober
 * @see FileField
 **/
class File
{
    /**
     * The name of the file.
     **/
    public $name;
    /**
     * The mime type of the file.
     **/
    public $type;
    /**
     * The path of the temporary file.
     **/
    public $tmp_name;
    /**
     * An error message, if there was an error uploading the file.
     **/
    public $error;
    /**
     * The size of the file in bytes.
     **/
    public $bytes;
    
    /**
     * @author Jeff Ober
     * @return null
     * @param array $file_data the uploaded file's array data from $_FILES
     **/
    public function __construct(array $file_data)
    {
        $this->name = $file_data['name'];
        $this->type = $file_data['type'];
        $this->tmp_name = $file_data['tmp_name'];
        $this->error = $file_data['error'];
        $this->bytes = $file_data['size'];
    }
    
    /**
     * Moves the files from the temporary directory to another location. The new
     * file will have the original file's name.
     * @author Jeff Ober
     * @return boolean true on success, false on error
     * @see File::$tmp_name,File::$name
     **/
    public function move_to($path)
    {
        $new_name = sprintf('%s/%s', $path, $this->name);
        move_uploaded_file($this->tmp_name, $new_name);
        return $new_name;
    }
}

/**
 * Image
 * 
 * Adds a few additional properties specific for images to the File class.
 * @author Jeff Ober
 * @see ImageField
 **/
class Image extends File
{
    /**
     * The image's width in pixels.
     **/
    public $width;
    /**
     * The image's height in pixels.
     **/
    public $height;
    
    public function __construct($file_data)
    {
        parent::__construct($file_data);
        list($this->width, $this->height) = getimagesize($this->tmp_name);
    }
}

?>
