<?php
class Response
{
    protected $title;
    protected $responseType = 'html'; // Можно наследованиями, но мне лень.
    protected $templateFile;
    protected $pageFile;
    
    public function title($title = null)
    {
        if (is_null($title))
            return $this -> title;
        
        $this -> title = $title;
        return $this;
    }
    
    public function templateFile($file)
    {        
        $this -> templateFile = $file;
        return $this;
    }
    
    public function pageFile($file)
    {
        $this -> pageFile = $file;
        return $this;
    }
    
    public function asHTML()
    {
        $this -> responseType = 'html';
        return $this;
    }
    
    public function asJSON()
    {
        $this -> responseType = 'json';
        return $this;
    }
    
    public function send(View $view)
    {
        switch ($this -> responseType)
        {
            case 'html':

                // Бррр, ну и мерзостный же код.
                // Быстрая разработка btw
                
                header('Content-type', 'text/html; charset=UTF-8');
                
                $container = new Container($view, $this);
                $container -> load($this -> pageFile);
                $view -> setParam('_pageContent', $container -> content);
                $container -> load($this -> templateFile);
                echo $container -> content;
                break;
                
            case 'json':
                header('Content-type', 'application/json.; charset=UTF-8');
                echo json_encode($view -> getParams());
                break;
        }
        
        return $this;
    }
}