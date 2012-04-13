<?php
/**
 *  [ Queue ]
 * 
 *  Реализует очередь в магазине. Входит как свойство в классе Store. 
 *  Очередь реализована в контексте конкретно выбранного магазина.
 */

class Queue extends ActiveRecord
{
    protected $store;
    
    protected $storeId;
    protected $personId;
    protected $personName;
    protected $personPosition;
    
    
    /**
     *  Автоматическое создание связи с объектом класса Store
     */
    
    public function __construct(Store $store = null)
    {
        if (!is_null($store)) $this -> setStore($store);
    }
    
    
    /**
     * Производить операции над этим объекта класса Store
     * 
     * @param Store $store 
     */
    
    protected function setStore(Store $store)
    {
        $this -> store = $store;
    }
    
    
    /**
     * Возвращает контекст, в котором работает объект этого класса.
     * 
     * @return Store
     */
    
    public function getStore()
    {
        return $this -> store;
    }
    
    
    /**
     * Добавляет человека в очередь данного магазина.
     * Этот метод не добавляет автоматичски его покупательскую корзину
     * 
     * @param Person $person
     * @return \Queue
     * @throws Exception 
     */
    
    public function add(Person $person)
    {
        if (self::inQueue($person))
            throw new Exception('Этот покупатель уже стоит в очереди в магазин');
        
        $this -> storeId  = $this -> store -> id;
        $this -> personId = $person -> id;
        $this -> personPosition = $this -> maxPosition()+1;
        $this -> store -> validateQueue($person);
        $this -> _insert();
        
        return $this;
    }
    
    
    /**
     * Удаляет покупатели из очереди в случаи существования его в очереди
     * и автоматически обновляет позиции остальных людей в очереди.
     * 
     * @param Person $person 
     */
    
    public function remove(Person $person)
    {
        $person -> basket -> drop();
        $personPosition = $this -> personPosition($person);

        self::$db -> query(
            'UPDATE Queue SET personPosition = personPosition-1'.
            ' WHERE storeId='.self::$db -> escape_string($this -> store -> id).
            ' AND personPosition>'.self::$db -> escape_string($personPosition));
        
        self::$db -> query(
            'DELETE FROM Queue '.
            ' WHERE storeId='.self::$db -> escape_string($this -> store -> id).
            ' AND personId='.self::$db -> escape_string($person -> id));
    }
    
    
    /**
     * Возвращает количество стоящих покупателей в очереди данного магазина.
     * 
     * @return type 
     */
    
    public function size()
    {
        $query = self::$db -> query(
            'SELECT COUNT(id) FROM Queue
             WHERE storeId='.self::$db -> escape_string($this -> store -> id));
        
        $fetch = $query -> fetch_array();
        return $fetch[0];
    }
    
    
    /**
     * Освобождает место #n в очереди.
     * 
     * @param type $personPosition 
     */
    
    public function free($personPosition)
    {
        self::$db -> query(
            'UPDATE Queue SET personPosition = personPosition+1'.
            ' WHERE storeId='.self::$db -> escape_string($this -> store -> id).
            ' AND personPosition>='.self::$db -> escape_string($personPosition));
    }
    
    
    /**
     * Возвращает список стоящих в очереди данного магазина покупателей как
     * массив записей Person (нужный класс автоматически выбирается при создании
     * объектов)
     * 
     * @return type 
     */
    
    public function ls()
    {
        return self::resultToRecords(
                self::_ls('*', array('storeId' => $this -> store-> id))
        );
    }

    
    /**
     * Расширенная версия ls, возвращает имена стоящих в очереди людей.
     * 
     * @return \static 
     */
    
    public function lsJoin()
    {        
        $query = 'SELECT Queue.*, Person.name as personName FROM Queue
                  LEFT JOIN Person ON Queue.personId=Person.id
                  WHERE Queue.storeId='.self::$db -> escape_string($this -> store -> id).
                ' ORDER BY Queue.personPosition';
        
        $query = self::$db -> query($query);
        
        $records = array();
        while ($fetch = $query -> fetch_assoc())
        {
            $record = new static();
            $record->setParams($fetch);
            $records[] = $record;
        }
        
        return $records;
    }
    
    
    /**
     * Возвращает storeId магазина, в очереди которой стоит покупатель, либо
     * false, если покупатель не стоит ни в одной из очередей
     * 
     * @param Person $person
     * @return boolean 
     */
    
    public static function inQueue(Person $person)
    {
        $query = self::$db -> query(
            'SELECT storeId FROM Queue '.
            'WHERE personId='.self::$db -> escape_string($person -> id));
        
        if (!$query -> num_rows) return false;
        $fetch = $query -> fetch_array();
        return $fetch[0];
    }
    
    
    /**
     * Возвращает позицию последнего участника очереди.
     * 
     * @return type 
     */
    
    public function maxPosition()
    {
        $query =
        self::$db -> query(
            'SELECT MAX(personPosition) FROM Queue '.
            'WHERE storeId='.self::$db -> escape_string($this -> store -> id));
        
        $fetch = $query -> fetch_array();
        return $fetch ? $fetch[0] : 0;
    }
    
    
    /**
     * Возвращает позицию покупатели в очереди, если он в ней присутствует.
     * 
     * @param Person $person
     * @return type 
     */
    
    public function personPosition(Person $person)
    {        
        $query = 
        self::$db -> query(
            'SELECT personPosition FROM Queue'.
           ' WHERE storeId='.self::$db -> escape_string($this -> store -> id).
           ' AND personId='.self::$db -> escape_string($person -> id));
        
        $fetch = $query -> fetch_array();
        return $fetch ? $fetch[0] : 0;
    }
}


/**
 *  Описание таблицы в БД 
 */

Queue::setTable('Queue', array(
                 'id' => 'int',
            'storeId' => 'int',
           'personId' => 'int',
    'personPosition'  => 'int'
));