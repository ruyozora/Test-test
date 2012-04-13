<?php
/**
 *  [ Store ]
 * 
 *  Реализация магазина.
 *  Также содержит в себе ссылки на собственный ассортимент и на собственную
 *  очередь.
 * 
 *  Класс абстрактен, использование:
 * 
 *      Store::factory($type)
 * 
 *  Где type - тип магазина, а именно grocery и hawdware.
 */

abstract class Store extends ActiveRecord
{
    public $assortment;
    public $queue;
    
    protected $name;
    protected $type;
    
    
    /**
     *  Автоматически создает необходимые  
     */
    
    public function __construct()
    {
        $this -> assortment = new Assortment($this);
        $this -> queue = new Queue($this);
    }
    
    
    /**
     * Возвращает новый соответсвующий типу объект класса, родительского от
     * Store.
     * 
     * @param type $type
     * @return \classname
     * @throws Exception 
     */
    
    public static function factory($type)
    {
        $type = trim(strtolower($type));
        
        if (!$type)
            throw new Exception('Store is abstract class');
        
        $classname = self::typeExists($type);
        if (!$classname || is_subclass_of('Store', $classname))
            throw new Exception('Unknown type "'.$type.'"');
        
        $result = new $classname;
        $result -> type = $type;
        
        return $result;
    }
    
    
    /**
     * Добавляет информацию об этом магазине в базу данных.
     * Информация о ассортименте и очереди автоматически не создается.
     * 
     * @return type
     * @throws Exception 
     */
    
    public function add()
    {
        if (!strlen($this -> name))
            throw new Exception('Отсутствует название магазина');

        if (!strlen($this -> type) || !self::typeExists($this -> type))
            throw new Exception('Отсутствует либо неизвестный тип магазига');
        
        return $this -> _insert();
    }
    
    
    /**
     * Удаляет(в случаи возможности)  информациию об этом магазине 
     * из базы данных
     * 
     * @return type
     * @throws Exception 
     */
    
    public function remove()
    {
        if ($this -> queue -> size())
            throw new Exception('В очереди в этот магазин стоят люди. Удалите '.
                'их из очереди, прежде удалять информацию о магазине.');

        $this -> assortment -> drop();
        return self::_sremove($this -> id);
    }
    
    
    /**
     * Возвращает объект данного класса с информацией, полученный по id
     * 
     * @param type $id
     * @return type 
     */
    
    public static function get($id)
    {
        $fetch = self::_get($id);
        $result = self::factory($fetch['type']);
        unset($fetch['type']);
        $result -> setParams($fetch);
        
        return $result;
    }
    
    
    /**
     * Человеко-понятное разъяснение своего типа.
     * 
     * @return string 
     */
    
    public static function explainType()
    {
        return 'Обычный магазин';
    }
    
    
    /**
     * Возвращает true в случаи, если магазин данного типа возможно создать.
     * 
     * @param type $type
     * @return type 
     */
    
    public static function typeExists($type)
    {
        $classname = 'Store'.ucfirst(strtolower($type));
        return class_exists($classname) ? $classname : false;
    }
    
    
    /**
     * Возвращает список всех существующих магазинов, в виде массива
     * объектов Store
     * 
     * @return array
     */
    
    public static function ls()
    {
        $records = array();
        $result  = self::_ls();
        
        while ($row = $result -> fetch_assoc())
        {
            $record = self::factory($row['type']);
            
            foreach ($row as $name => $value)
                if ($name != 'type')
                    $record -> __set($name, $value);
            
            $records[] = $record;
        }
        
        return $records;
    }
    
    
    /**
     * Вызывает при попытки вставки покупателя в очередь данного магазина
     * 
     * @param Person $person
     * @return boolean 
     */
    
    public function validateQueue(Person $person)
    {
        return true;
    }
    
    
    /**
     * Запрет менять type
     * 
     * @param type $name
     * @param type $value
     * @throws Exception 
     */
    
    public function __set($name, $value)
    {
        if ($name == 'type')
        {
            throw new Exception('Запрещено менять тип записей');
        }
        else
        {
            parent::__set($name, $value);
        }
    }
}


/**
 *  Описание таблицы в БД 
 */

Store::setTable('Store', array(
    'id'   => 'int',
    'name' => 'tinytext',
    'type' => 'tinytext'
));