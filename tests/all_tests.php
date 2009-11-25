<?php

require_once 'simpletest/autorun.php';

class AllTests extends TestSuite
{
    function AllTests()
    {
        $this->TestSuite('All Phorms Tests');

        $this->addFile(dirname(__FILE__) . '/autoloader_tests.php');
        
        $this->addFile(dirname(__FILE__) . '/form_tests.php');
                
        $this->addFile(dirname(__FILE__) . '/field_tests/charfield_test.php');

        $this->addFile(dirname(__FILE__) . '/field_tests/hiddenfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/passwordfield_test.php');
        
        $this->addFile(dirname(__FILE__) . '/field_tests/textfield_test.php');
    }
}

?>