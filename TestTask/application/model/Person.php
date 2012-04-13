<?php
/*
 *  [ Person ]
 * 
 *  Реализация покупателя. 
 *  Содержит в себе информацию о покупательской корзине.
 * 
 *  Класс абстрактен, использование:
 * 
 *      Person::factory($type)
 * 
 *  Где type - тип покупателя, а именно adult и veteran
 */

abstract class Person extends ActiveRecord
{
    protected $qId;
    protected $name;
    protected $type;
    protected $basket = false;
    
    
    /**
     *  Автоматически создаем корзину
     */
    
    public function __construct()
    {
        $this -> basket = new Basket($this);
    }
    
    
    /**
     * Возвращает новый соответсвующий типу объект класса, родительского от
     * Person.
     * 
     * @param type $type
     * @return \classname
     * @throws Exception 
     */
    public static function factory($type)
    {
        $type = trim(strtolower($type));
        
        if (!$type)
            throw new Exception('Person is abstract class');
        
        $classname = self::typeExists($type);
        if (!$classname || is_subclass_of('Person', $classname))
            throw new Exception('Unknown type "'.$type.'"');
        
        $result = new $classname;
        $result -> type = $type;
        
        return $result;
    }
    
    
    /**
     * Добавляет информацию о покупателе в базу данных.
     * 
     * @return type
     * @throws Exception 
     */
    
    public function add()
    {
        if (!strlen($this -> name))
            throw new Exception('Отсутствует имя человека');

        if (!strlen($this -> type) || !self::typeExists($this -> type))
            throw new Exception('Отсутствует  либо неизвестный тип человека');
        
        return $this -> _insert();
    }
    
    
    /**
     * Удаляет (в случаи существования) информацию о покупателе.
     * 
     * @return type
     * @throws Exception 
     */
    
    public function remove()
    {
        if (Queue::inQueue($this))
            throw new Exception('Этот покупатель стоит в очереди в один из магазинов.');
        
        $this -> basket -> drop();
        return self::_sremove($this -> id);
    }
    
    
    /**
     * Возвращает объект данного класса, полученный по id
     * 
     * @param type $id
     * @return type 
     */
    
    public static function get($id)
    {
        $fetch  = self::_get($id);
        $result = self::factory($fetch['type']);
        unset($fetch['type']);
        $result -> setParams($fetch);
        
        return $result;
    }
    
    
    /**
     * Возвращает true в случаи, если покупателя данного типа возможно создать.
     * 
     * @param type $type
     * @return type 
     */
    
    public static function typeExists($type)
    {
        $classname = 'Person'.ucfirst(strtolower($type));
        return class_exists($classname) ? $classname : false;
    }
    
    
    /**
     * Человеко-понятное разъяснение своего типа.
     * 
     * @return string 
     */
    
    public static function explainType()
    {
        return 'Обычный человек';
    }
    
    
    /**
     * Возвращает список всех существующих магазинов, в виде массива
     * объектов Person
     * 
     * @return type 
     */
    
    public static function ls()
    {
        $records = array();
        
        $result = self::$db -> query('
            SELECT Person.*, Queue.id as qId FROM Person
            LEFT JOIN Queue ON Queue.personId = person.id
            ORDER BY id');
                
        while ($row = $result -> fetch_assoc())
        {
            $record = self::factory($row['type']);
            
            foreach ($row as $name => $value)
                $record -> __set($name, $value);
            
            $records[] = $record;
        }
        
        return $records;
    }
}


/**
 *  Описание таблицы в БД 
 */

Person::setTable('Person', array(
    'id'   => 'int',
    'name' => 'tinytext',
    'type' => 'tinytext'
));