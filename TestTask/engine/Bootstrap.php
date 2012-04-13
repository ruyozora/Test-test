<?php
/*
 *  [ Bootstrap.php ]
 * 
 *  Filename : engine/Bootstrap.php
 *      Date : 11.04.2012
 * 
 *  Для работы приложения обязательны следующие константы:
 * 
 *      PATH_APPLICATION    - путь к приложению, к проекту
 *      PATH_ENGINE         - путь к MVC-движку
 * 
 *  В противном случаи приложение не запустится.
 */

if (!defined('PATH_ENGINE') ||
    !defined('PATH_APPLICATION'))
die('Please define PATH_ENGINE and PATH_APPLICATION');


/*
 *  Определение путей к различным частям приложения
 */

define ('PATH_MODELS', PATH_APPLICATION.'/model');
define ('PATH_CONTROLLERS', PATH_APPLICATION.'/controller');
define ('PATH_LAYOUTS', PATH_APPLICATION.'/layout');


/**
 *  Дефолтный загрузчик 
 */

class Bootstrap
{
    protected static $_instance;
    
    public static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }
    
    public function autoload()
    {
        spl_autoload_register(function($className)
        {
            if (file_exists(PATH_ENGINE.'/core/'.$className.'.php'))
            {
                $file = PATH_ENGINE.'/core/'.$className.'.php';
            }
            else if (preg_match_all('/^(.*)Model$/', $className, $matches))
            {
                $file = PATH_MODELS.'/'.$matches[1][0].'.php';
            }
            else if (file_exists(PATH_MODELS.'/'.$className.'.php'))
            {
                $file = PATH_MODELS.'/'.$className.'.php';
            }

            if (!isset($file) || !file_exists($file))
                return false;

            require_once $file;            
        });
        
        return $this;
    }
    
    public function run()
    {
        Application::getInstance() -> run()
                                   -> sendResponse();
    }
}