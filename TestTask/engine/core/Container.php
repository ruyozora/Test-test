<?php
class Container
{
    public $view;
    public $response;
    public $content;
    
    public function __construct(View $view, Response $response) 
    {
        $this -> view = $view;
        $this -> response = $response;
    }
    
    public function __get($name)
    {
        return $this -> view -> getParam($name);
    }
    
    public function __set($name, $value)
    {
        $this -> view -> setParam($name, $value);
    }
    
    public function __isset($name) 
    {
        return $this -> view -> issetParam($name);
    }
    
    public function title($title=null)
    {
        return $this -> response -> title();
    }
    
    public function view()
    {
        return $this -> view;
    }
    
    public function response()
    {
        return $this -> response;
    }
    
    public function load($file)
    {
        if (!file_exists($file))
            return;
        
        ob_start();
        include $file;
        $this -> content = ob_get_clean();
    }
}