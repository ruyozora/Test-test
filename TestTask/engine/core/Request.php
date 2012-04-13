<?php
/*
 *  [ Request ]
 * 
 *  Filename : request.php
 *      Date : 07.04.2012
 * 
 *  Сбор запроса
 */

class Request
{
    protected static $_instance;
    protected $vars;
    protected $controllerName = false;
    protected $actionName = false;

    
    /**
     *  Возвращает экземпляр данного класса. 
     */
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
 
        return self::$_instance;
    }
    
    
    /**
     * Возвращает контроллер, который был запрошен.
     * 
     * @return type 
     */
    
    public function getControllerName()
    {
        return $this -> controllerName;
    }
    
    
    /**
     * Возврашает действите над контроллером, которое было запрошено.
     * 
     * @return type 
     */
    
    public function getActionName()
    {
        return $this -> actionName;
    }
    
    
    /**
     * Получает контроллер и действие. 
     */
    
    public function getControllerActionRequest($c, $a)
    {
        $this -> controllerName = $this -> g($c);
        $this -> actionName = $this -> g($a);
    }
    
    
    /**
     * Возврашает параметр запроса:
     *      - из внутреннего хранилища
     *      - из GET, POST, COOKIE, SESSION
     * 
     * @param type $name
     * @return type
     * @throws Exception 
     */
    
    public function getParam($name)
    {
        if (isset($this -> vars[$name]))
            return $this -> vars[$name];
        
        if (isset($_GET[$name]))
            return $_GET[$name];
        
        if (isset($_POST[$name]))
            return $_POST[$name];
        
        if (isset($_COOKIE[$name]))
            return $_COOKIE[$name];
        
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
        
        throw new Exception('Unknown request var "'.$name.'"');
    }
    
    
    /**
     * Сохранить в Request параметр запроса
     * 
     * @param type $name
     * @param type $value 
     */
    
    public function setParam($name, $value)
    {
        switch ($name)
        {
            case 'controller':
                $this -> controllerName = $value;
                break;
            
            case 'action':
                $this -> actionName = $value;
                break;
            
            default:
                $this -> vars[$name] = $value;
        }
    }
    
    
    /**
     * Сохранить массив как массив дополнительных параметров запроса.
     * 
     * @param array $params 
     */
    
    public function setParams(Array $params)
    {
        foreach ($params as $name => $value)
            $this -> setParam($name, $value);
    }
    
    
    /**
     * $_GET + внутреннее хранилище
     * Переменные внутреннего хранилища приравниваются к параметрам, полученными
     * из $_GET
     * 
     * @param type $name
     * @return type 
     */
    
    public function g($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : (
               isset($this -> vars[$name]) ? $this -> vars[$name] : null);
    }
    
    
    /**
     * $_POST
     * 
     * @param type $name
     * @return type 
     */
    
    public function p($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : (
               isset($this -> vars[$name]) ? $this -> vars[$name] : null);
    }
    
    
    /**
     * $_COOKIE
     * 
     * @param type $name
     * @return type 
     */
    
    public function cookie($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }
    
    
    /**
     * $_SESSION
     * 
     * @param type $name
     * @return type 
     */
    
    public function session($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
}