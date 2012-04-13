<?php
class Table
{
    protected $name;
    protected $fields;
    
    public function __construct ($tablename, Array $fields = null)
    {
        $this -> setName   ($tablename);
        $this -> setFields ($fields);
    }
    
    protected function setName($name)
    {
        $this -> name = $name;
        return true;
    }
    
    public function getName()
    {
        return $this -> name;
    }
    
    protected function setFields(Array $fields = null)
    {
        foreach ($fields as $name => $type)
        {
            $this -> fields[$name] = array('name' => $name,
                                           'type' => $type);
        }
        
        return true;
    }
    
    public function getField($name)
    {
        if (!isset($this -> fields[$name]))
            throw new Exception('Unknown field "'.$name.'"');
        
        return $this -> fields[$name];
    }
    
    public function getFields()
    {
        return $this -> fields;
    }
    
    public function fieldExists($name)
    {
        return isset($this -> fields[$name]);
    }
}