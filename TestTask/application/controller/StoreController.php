<?php
class StoreController extends Controller
{
    public function Index()
    {
        return array (  'stores' => Store::ls(),
                      'products' => Product::ls());
    }
    
    public function Add()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $name = $this -> _request() -> p('name');
            $type = $this -> _request() -> p('type');
            
            $store = Store::factory($type);            
            $store -> name = trim($name);
            $store -> add();
            
            return array('id'   => $store -> id,
                         'name' => $store -> name,
                         'type' => $store -> type,
                         'expl' => $store::explainType());
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
            $store = Store::get($this -> _request() -> g('id'));
            $store -> remove();
            
            return array('success' => 1);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    } 
}
