<?php
class QueueController extends Controller
{
    public function Index()
    {
        return array( 'stores' => Store::ls(),
                     'persons' => Person::ls(),
                    'products' => Product::ls());
    }
    
    
    public function Add()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $personId = $this -> _request() -> p('personId');
            $storeId  = $this -> _request() -> p('storeId');
            
            $store  = Store::get($storeId);
            $person = Person::get($personId); 
            
            $store  -> queue -> add($person);
            $person -> basket -> drop();
           
            foreach ($_POST as $name => $value)
            {
                if (preg_match_all('/^product_(\d+)$/', $name, $matches))
                {
                    
                    $person -> basket -> add(Product::get($matches[1][0]));
                }
            }
             
            return array('success' => 1);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
    
    
    public function Ls()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $store = Store::get($this -> _request() -> g('storeId'));

            $arr = array();
            foreach ($store -> queue -> lsJoin() as $record)
            {
                $arr[] = array(
                    'id' => $record -> id,
                    'personId' => $record -> personId,
                    'personName' => $record -> personName,
                    'personPosition' => $record -> personPosition
                );
            }
            
            return $arr;
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
            $store = Store::get($this ->_request() -> g('storeId'));
            $person = Person::get($this ->_request() -> g('personId'));
            
            $store -> queue -> remove($person);
            
            return array('success' => 1);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
}