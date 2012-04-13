<?php
class ProductController extends Controller
{
    public function Index()
    {
        return array('products' => Product::ls());
    }
    
    public function Add()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $name = $this -> _request() -> p('name');
            
            $product = new Product();
            $product -> name = trim($name);
            $product -> add();
            
            return array('id'   => $product -> id,
                         'name' => $product -> name);
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
            Product::sremove($this -> _request() -> g('id'));
            return array('success' => true);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
}