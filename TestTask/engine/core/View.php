<?php
/*
 *  [ View ]
 * 
 *  Filename : view.php
 *      Date : 05.02.2012
 * 
 *  Вид.
 */

class View
{
    protected $vars;
    protected $response;
    
    public function setParam($name, $value)
    {
        $this -> vars[$name] = $value;
    }
    
    public function setParams(Array $params = null)
    {
        if (is_array($params))
            foreach ($params as $name => $value)
                $this -> setParam ($name, $value);
    }
    
    public function issetParam($name)
    {
        return isset($this -> vars[$name]);
    }
    
    public function getParam($name)
    {
        if (!isset($this -> vars[$name]))
            throw new Exception('Unknown param "'.$name.'"');
        
        return $this -> vars[$name];
    }
    
    public function getParams()
    {
        return $this -> vars;
    }
    
    public function setResponse(Response $response)
    {
        $this -> response = $response;
    }
    
    public function getResponse()
    {
        return $this -> response;
    }
}