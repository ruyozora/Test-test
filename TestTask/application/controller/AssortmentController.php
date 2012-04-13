<?php
class AssortmentController extends Controller
{
    /**
     * Возвращает в виде JSON-данных список продуктов, имеющихся в ассортименте
     * магазина(по ID магазина, $_GET['storeId'])
     * 
     *  Ответ: array (id, productId, name)
     * 
     * @return type 
     */
    
    public function Ls()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $store = Store::get($this -> _request() -> g('storeId'));

            $arr = array();
            foreach ($store -> assortment -> lsJoin() as $record)
            {
                $arr[] = array(
                           'id' => $record -> id,
                    'productId' => $record -> productId,
                         'name' => $record -> productName
                );
            }
            
            return $arr;
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
    
    
    /**
     * Сохраняет информацию об ассортименте магазина.
     * 
     *  Принимает: storeId, product_id1, product_id2, ...
     * 
     * @return type 
     */
    
    public function Save()
    {
        $this -> _response() -> asJSON();
        
        try
        {
            $store = Store::get($this -> _request() -> p('storeId'));
            $map = array();
            
            foreach ($_POST as $name => $value)
            {
                if (preg_match_all('/^product_(\d+)$/', $name, $matches))
                {
                    $map[$matches[1][0]] = $value;
                }
            }
            
            $store -> assortment -> map($map);
            return array('success' => true);
        }
        catch (Exception $e)
        {
            return array('error' => $e -> getMessage());
        }
    }
}