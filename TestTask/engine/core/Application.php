<?php
class Application
{
    protected static $_instance;
    
    public $mysqli;
    public $request;
    public $dispatcher;
    public $view;
    public $response;
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
 
        return self::$_instance;
    }
    
    public function __construct() 
    {
        $this -> request = Request::getInstance();
        $this -> dispatcher = new Dispatcher();
        $this -> view = new View();
        $this -> response = new Response();       
    }
        
    public function getRequest()
    {
        $this -> request -> getControllerActionRequest('controller', 'action');
        
        if (!$this -> request -> getControllerName())
             $this -> request -> setParam('controller', $this -> dispatcher -> getDefaultController());

        if (!$this -> request -> getActionName())
             $this -> request -> setParam('action', $this -> dispatcher -> getDefaultAction());
        
        return $this;
    }
    
    public function connectDb($config)
    {
        /**
         *  Это, конечно, не самое красивое решение, но лучше уж написать код
         *  проще, чем вводить какие-нибудь там конфиги только для одного-един-
         *  ственного файла. 
         */
        
        $c = include PATH_APPLICATION.'/config/'.$config;
        
        $this -> mysqli = new MysqliE($c['hostname'], $c['username'], 
                                      $c['password'], $c['database']);
        
        if ($this -> mysqli -> connect_error)
            throw new Exception('Connect error:'. $this -> mysqli -> connect_error);
        
        ActiveRecord::setDb($this -> mysqli);
        return $this;
    }
    
    public function dispatch()
    {
        $this -> view -> setParams( 
        $this -> dispatcher -> setSearchDir(PATH_CONTROLLERS)
                            -> create($this -> request -> getControllerName())
                            -> run($this -> request -> getActionName()));
            
        return $this;
    }
    
    
    public function run()
    {
        try
        {
            $this -> getRequest()
                  -> connectDb('mysqli.php')
                  -> dispatch();
        }
        catch (Exception $e)
        {
            die
            ('<pre>
                Uncatched application exception #'. $e -> getCode() .":
                ".$e -> getMessage()."
                In file \"".$e -> getFile()."\", line ".$e -> getLine()."\n</pre>");
        }
        
        return $this;
    }
    

    public function sendResponse()
    {
        $pagefile = $this -> request -> getControllerName() .'.'.
                    $this -> request -> getActionName().'.php';

        $this -> response -> templateFile(PATH_LAYOUTS.'/template/default.php')
                          -> pageFile(PATH_LAYOUTS.'/pages/'.$pagefile)
                          -> send($this -> view);
        
        return $this;
    }
}
