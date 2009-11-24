<?php

require_once 'simpletest/autorun.php';

class AllTests extends TestSuite
{
    function AllTests()
    {
        $this->TestSuite('All Phorms Tests');
        
        /**
         * AutoLoaderTestCase should run first so the Auto Loader is working.
         */
        $this->addFile(dirname(__FILE__) . '/autoloader_tests.php');
        
        $this->addFile(dirname(__FILE__) . '/form_tests.php');
    }
}

?>