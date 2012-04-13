<?php
class Controller
{
    public function Index()
    {
        // ...
    }
    
    public function _request()
    {
        return Application::getInstance() -> request;
    }
    
    public function _response()
    {
        return Application::getInstance() -> response;
    }
    
    public function __get($name)
    {
        return Application::getInstance() -> view -> getParam($name);
    }
    
    public function __set($name, $value)
    {
        return Application::getInstance() -> view -> setParam($name, $value);
    }
}