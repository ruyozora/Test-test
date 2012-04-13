<?php
class PersonController extends Controller
{
    public function Index()
    {
        return array ( 'persons' => Person::ls(),
                      'products' => Product::ls());
    }
    
    public function Add()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $name = $this -> _request() -> p('name');
            $type = $this -> _request() -> p('type');
            
            $person = Person::factory($type);
            
            $person -> name = trim($name);
            $person -> type = trim($type);
            $person -> add();
            
            return array('id'   => $person -> id,
                         'name' => $person -> name,
                         'type' => $person -> type,
                         'expl' => $person::explainType());
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
    
    public function Remove()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $person = Person::get($this -> _request() -> g('id'));
            $person -> remove();
            
            return array('success' => 1);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
}