<?php
class StoreGrocery extends Store
{
    public static function explainType()
    {
        return 'Продуктовый';
    }
    
    public function validateQueue(Person $person)
    {
        if ($this -> queue -> size() >= 10)
            throw new Exception('В этот магазин могут встать не более 10 покупателей');
        
        return parent::validateQueue($person);
    }
}
