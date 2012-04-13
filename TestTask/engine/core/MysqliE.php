<?php
class MysqliE extends mysqli
{
    function query($query)
    {
        $result = parent::query($query);
        
        if ($this -> error)
            throw new Exception($this -> error);
        
        return $result;
    }
}