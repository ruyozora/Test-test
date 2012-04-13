<?php
/*
 *  [ Dispatcher ]
 * 
 *  Filename : dispatcher.php
 *      Date : 10.04.2012
 * 
 *  Диспатчер, класс, который "запускает" связку контроллер-действие.
 */

class Dispatcher
{
    protected $searchDir = '.';
    protected $controller;
    protected $defaultController = 'Index';
    protected $defaultAction     = 'Index';

    
    /**
     * Возвращает путь, в котором ищутся контроллеры
     * 
     * @return type  
     */
    
    public function getSearchDir()
    {
        return $this -> searchDir;
    }
    
    
    /**
     * Установить контроллер по дефолту.
     * 
     * @param type $defaultController
     * @return \Dispatcher 
     */
    
    public function setDefaultController($defaultController)
    {
        $this -> defaultController = $defaultController;
        return $this;
    }

    
    /**
     * Возвращает имя дефолтного контроллера
     * 
     * @return type 
     */
    
    public function getDefaultController()
    {
        return $this -> defaultController;
    }
    
    
    /**
     * Установить дефолтное действие
     * 
     * @param type $defaultAction
     * @return \Dispatcher 
     */
    
    public function setDefaultAction($defaultAction)
    {
        $this -> defaultAction = $defaultAction;
        return $this;
    }
    
    
    /**
     * Возвращает название дефолтного действия
     * 
     * @return type 
     */
    
    public function getDefaultAction()
    {
        return $this -> defaultAction;
    }
    
    
    /**
     * Установить путь, в котором требуется искать контроллеры
     * 
     * @param type $path
     * @return \Dispatcher 
     */
    
    public function setSearchDir($path)
    {
        $this -> searchDir = $path;
        return $this;
    }
    
    /**
     * Создать и установить текущим новый контроллер.
     * 
     * @param string $controllerName Имя контроллера
     */
    
    public function create($controllerName)
    {
        if (!$controllerName)
            $controllerName = $this -> defaultController;
        
        $controllerName = ucfirst(strtolower($controllerName)).'Controller';
        $controllerFilename = $this -> searchDir.'/'.$controllerName.'.php';
        
        if (preg_match('/[^a-zA-Z0-9]/', $controllerName))
            throw new Exception('Invalid controller name', 404);
        
        if (!file_exists($controllerFilename)) 
            throw new Exception('Unknown controller "'.$controllerName.'"', 404);
        
        require_once $controllerFilename;
        
        if (!class_exists($controllerName) || !is_subclass_of($controllerName, 'Controller'))
            throw new Exception('Unknown controller "'.$controllerName.'"', 404);
        
        $this -> controller = new $controllerName();
        return $this;
    }
    
    
    /**
     * Возвращает созданный контроллер.
     * 
     * @return Controller
     */
    
    public function getController()
    {
        return $this -> controller;
    }
    

    /**
     * Выполнить действие(метод) созданного контроллера.
     * 
     * @param string $actionName Метод
     * @return mixed
     */
    
    public function run($actionName)
    {
        $actionName = ucfirst(strtolower($actionName));
        
        if (!$actionName)
            $actionName = $this -> defaultAction;
        
        if (preg_match('/\_?[^a-zA-Z0-9]/', $actionName))
            throw new Exception('Invalid action name', 404);
        
        if (!is_object($this -> controller))
            throw new Exception('No controller');
        
        if (!method_exists($this -> controller, $actionName))
            throw new Exception('Unknown action "'.$actionName.'"', 404);

        return $this -> controller -> $actionName();
    }
}
