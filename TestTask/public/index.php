<?php
/*
 *  [ Тестовое задание ]
 * 
 *  Filename : index.php
 *      Date : 10.04.2012
 */

define ('PATH_APPLICATION', dirname(__FILE__).'/../application');
define ('PATH_ENGINE', dirname(__FILE__).'/../engine');

require_once (PATH_ENGINE.'/Bootstrap.php');

set_error_handler(function($code, $text, $file, $line)
{
    die("[$line] [$file] $text");
});

Bootstrap::getInstance() -> autoload()
                         -> run();