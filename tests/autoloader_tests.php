<?php

require_once 'simpletest/autorun.php';
require_once dirname(__FILE__) . '/../src/Phorms/Utilities/AutoLoader.php';

class AutoLoaderTestCase extends UnitTestCase
{
    function testRegisteringAutoLoader()
    {
        $this->assertTrue(Phorms_Utilities_AutoLoader::register());
    }
    
    function testAutoLoadingClasses()
    {
        $this->assertTrue(class_exists('Phorms_Forms_Form', TRUE));
    }
}

?>