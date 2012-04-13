<?php
/*
 *  [ TestBootstrap.php ]
 * 
 *  Filename : engine/Bootstrap.php
 *      Date : 11.04.2012
 * 
 *  Бутстрап для тестов.
 */
define ('PATH_APPLICATION', dirname(__FILE__).'/../application');
define ('PATH_ENGINE', dirname(__FILE__));

require_once (PATH_ENGINE.'/Bootstrap.php');

class TestBootstrap extends Bootstrap
{
    public function run()
    {
        Application::getInstance() -> getRequest()
                                   -> connectDb('mysqli_test.php');
    }
}

TestBootstrap::getInstance() -> autoload()
                             -> run();