<?php
class BasketController extends Controller
{    
    /**
     * Сохраняет информацию об корзине покупателя
     * 
     *  Принимает: personId, product_id1, product_id2, ...
     * 
     * @return type 
     */
    
    public function Save()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $person = Person::get($this -> _request() -> p('personId'));
            $person -> basket; $map = array();
            
            foreach ($_POST as $name => $value)
            {
                if (preg_match_all('/^product_(\d+)$/', $name, $matches))
                {
                    $map[$matches[1][0]] = $value;
                }
            }
            
            $person -> basket -> map($map);
            return array('success' => true);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
}