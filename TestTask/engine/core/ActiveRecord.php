<?php
/**
 *  [ ActiveRecord ]
 *  
 *  Filename : engine/core/ActiveRecord.php
 *  Date : 11.04.2012 
 *  
 *  Очень упрощенная реализация ActiveRecord
 */

class ActiveRecord
{
    protected static $db;
    protected static $_table = array();
    protected $id;
    
    
    public static function setDb(Mysqli $mysqli)
    {
        self::$db = $mysqli;
    }
    
    public static function getDb()
    {
        return self::$db;
    }
    
    
    /**
     * Контроль доступа к свойствам
     * 
     * @param type $name
     * @return type
     * @throws Exception 
     */
    
    public function __get($name)
    {
        if (!property_exists(get_called_class(), $name))
            throw new Exception('Unknown propery "'.$name.
                                '" of ActiveRecord "'.get_called_class().'"');
                
        return $this -> $name;
    }
    
    
    /**
     * Контроль установки значений свойствам.
     * Запрещено внешне менять id и _table.
     * 
     * @param type $name
     * @param type $value
     * @throws Exception 
     */
    
    public function __set($name, $value)
    {
        if (!property_exists(get_called_class(), $name))
            throw new Exception('Unknown propery "'.$name.
                                '" of ActiveRecord "'.get_called_class().'"');
        
        switch ($name)
        {
            case 'db':
            case '_table':
                throw new Exception('"id", "db" and "_table" are protected properties');
            
            case 'id':
                self::isValidId($value);
                $this -> id = $value;
                
            default:
                $this -> $name = $value;
        }
    }
    
    
    /**
     *
     * @param array $params 
     */
    
    public function setParams($params)
    {
        if (is_array($params))
        {
            foreach ($params as $name => $value)
                $this -> __set($name, $value);            
        }
        else if(is_object($params) && is_subclass_of($params, 'mysqli_result'))
        {
            $row = mysqli_fetch_assoc($result);
            
            foreach ($row as $name => $value)
                $this -> __set($name, $value);
        }
        else if (is_object($params) && is_subclass_of($params, 'ActiveRecord'))
        {
            foreach (self::getTable() -> getFields() as $field)
            {
                $name = $field['name'];
                if (isset($params -> $name))
                    $this -> __set($name, $params -> __get($name));
            }
        }
        else
        {
            throw new Exception('Unknown type of params');
        }
    }
    
    
    /**
     * Определение, описание таблицы, к которой привязан данный класс.
     * 
     * @param type $tablename
     * @param array $fields Массив name => type (tinytext, int, ...)
     */
    
    public static function setTable($tablename, Array $fields = null)
    {
        self::$_table[get_called_class()] = new Table($tablename, $fields);
    }
    
    
    /**
     * Возвращает привязанную к данной записи описание таблицы.
     * 
     * @return Table
     */
    
    public static function getTable()
    {
        $classname = get_called_class();
        
        do
        {
            if (isset(self::$_table[$classname])) return self::$_table[$classname];
        }
        while ($classname = get_parent_class($classname));
        
        throw new Exception('No table defination for "'.get_called_class().'"');
    }
    
    
    /**
     * Валидно значение в качестве id?
     * 
     * @param type $id
     * @param type $throwException
     * @throws Exception 
     */
    
    public static function isValidId($id, $throwException = false)
    {
        if (!is_numeric($id) || $id < 0)
        {
            if ($throwException) throw new Exception('Invalid id');
            return false;
        }
        
        return true;
    }
    
    
    /**
     *  Преобразовывает результаты mysqli_result в записи ActiveRecord
     */
    
    protected static function resultToRecords(mysqli_result $result)
    {
        $res = array();
        
        while ($row = $result -> fetch_assoc())
        {
            $record = new static();

            foreach ($row as $name => $value)
                $record -> __set($name, $value);
            
            $res[$record -> id] = $record;
        }
        
        return $res;
    }
    
    
    /**
     * Получает все записи из БД. 
     * 
     * @return mysqli_result
     */
    
    protected static function _ls($whatSelect = '*', Array $whereFields = null)
    {
        if (is_array($whatSelect))
        {
            foreach ($whatSelect as $name)
            {
                if (!self::getTable() -> fieldExists($name))
                    throw new Exception('Unknown field "'.$name.'"');
            }
            
            $whatSelect = implode(',', $whatSelect);
        }
        
        if (is_array($whereFields))
        {
            $where = array();
            
            foreach ($whereFields as $name => $value)
            {
                $field = self::getTable() -> getField($name);
                
                switch (strtolower($field['type']))
                {
                    case 'int':
                    case 'float':
                    case 'double':
                        break;
                    
                    default:
                        $value = '"'.$value.'"';
                }
                
                $where[] = $name.'='.$value;
            }
        }
        
        $query  = 'SELECT '.$whatSelect.' ';
        $query .= 'FROM '.self::$db -> escape_string(self::getTable() -> getName());
        
        if (isset($where) && is_array($where))
        {
            $query .= ' WHERE '.implode(' AND ',$where);
        }

        return self::$db -> query($query);
    }
    
    
    /**
     * Возвращает запись с данными, полученными из БД
     * 
     * @param type $id 
     * @return mysqli_result
     */
    
    public static function _get($id)
    {
        self::isValidId($id, true);
        
        $query = self::$db -> query(
            'SELECT * FROM '.
                self::$db -> escape_string(self::getTable() -> getName()).
            ' WHERE id='.$id);
        
        $fetch = $query -> fetch_assoc();
        
        if (!$fetch)
            throw new Exception('Unknown record with id"'.$id.'"');
        
        return $fetch;
    }
    
    
    /**
     *  Вставка записи в БД 
     */
    
    protected function _insert()
    {
        foreach (self::getTable() -> getFields() as $field)
        {
            if ($field['name'] != 'id')
            {
                $value = self::$db -> escape_string($this -> __get($field['name']));
                
                switch (strtolower($field['type']))
                {
                    case 'int':
                    case 'float':
                    case 'double':
                        if (!$value) $value = 0;
                        break;
                    
                    default:
                        $value = '"'.$value.'"';
                }
                
                $names[]  = $field['name'];
                $values[] = $value;
            }
        }
        
        $query = self::$db -> query('INSERT INTO '.
                    self::$db -> escape_string(self::getTable() -> getName())
                .' ('.implode(',', $names).') VALUES('.implode(',', $values).');');
     
        $this -> id = self::$db -> insert_id;
        return true;
    }
    
    
    /**
     * Статическая функция по удалению записи
     * 
     * @param type $id
     * @return boolean
     * @throws Exception 
     */
    
    protected static function _sremove($id)
    {
        self::isValidId($id, true);

        self::$db -> query(
            'DELETE FROM '.self::$db -> escape_string(self::getTable() -> getName()).' '.
            'WHERE id='.self::$db -> escape_string($id));
        
        return true;
    }
    
    
    /**
     *  
     */
    
    public function _remove()
    {
        return self::_sremove($this -> id);
    }
    
    /**
     *  Сохранение изменений записи. 
     */
    
    protected function _save()
    {
        // ...
    }
}