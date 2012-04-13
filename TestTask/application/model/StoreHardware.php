<?php
class StoreHardware extends Store
{
    public static function explainType()
    {
        return 'Хозяйственный';
    }
    
    public function validateQueue(Person $person)
    {
        
        if ($person -> type == 'veteran')
        {
            $query = self::$db -> query('
                SELECT MAX(Queue.personPosition) as mp, Person.type
                FROM Queue
                LEFT JOIN Person ON Queue.personId=Person.id
                WHERE Queue.storeId = '.self::$db -> escape_string($this -> id).'
                GROUP BY Person.type
                HAVING Person.type = "veteran"');
            
            
            if ($query -> num_rows)
            {
                $fetch = $query -> fetch_array();
                $this -> queue -> personPosition = $fetch[0]+1;
                $this -> queue -> free($fetch[0]+1);
            }
            else
            {
                $this -> queue -> personPosition = 1;
                $this -> queue -> free(1);
            }
        }
        
        return parent::validateQueue($person);
    }
}