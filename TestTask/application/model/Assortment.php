<?php
/*
 *  [ Assortment ]
 * 
 *  Реализация ассортимента магазина, входит как свойство в составе
 *  класса Store. Позволяет манипулировать ассортиментом
 *  в контексте конкретно выбранного магазина
 */

class Assortment extends ActiveRecord
{
    protected $store;
    protected $storeId;
    protected $productId;
    protected $productName;
    
    
    /**
     * Задать контекст, ассортимент *какого именно* магазина
     * 
     * @param Store $store 
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
     * Добавить продукт в ассортимент
     * 
     * @param Product $product
     * @return type
     * @throws Exception 
     */
    
    public function add(Product $product)
    {
        if (!$this -> store)
            throw new Exception('No store selected');
        
        if ($this -> isUsed($product))
            throw new Exception('Этот продукт уже есть в ассортименте одного из магазинов');
        
        $this -> storeId   = $this -> store -> id;
        $this -> productId = $product -> id;
        
        return self::_insert();
    }
    
    
    /**
     * Удалить продукт из ассортимента, если он там присутствует.
     * 
     * @param Product $product
     * @return boolean
     * @throws Exception 
     */
    
    public function remove(Product $product)
    {
        if (!$this -> store)
            throw new Exception('No store selected');        

        $this -> storeId   = $this -> store -> id;
        $this -> productId = $product -> id;
        
        self::$db -> query(
            'DELETE FROM Assortment WHERE '.
            'storeId='.self::$db -> escape_string($this -> storeId).
            ' AND productId='.self::$db -> escape_string($this -> productId));
        
        return true;
    }
    
    
    /**
     * Возврашает true в случаи существовании данного продукта в ассортименте
     * магазина
     * 
     * @param Product $product
     * @return type
     * @throws Exception 
     */
    
    public function exists(Product $product)
    {
        if (!$this -> store)
            throw new Exception('No store selected');  
        
        return self::$db -> query(
            'SELECT COUNT(id) FROM Assortment
             WHERE storeId='.self::$db -> escape_string($this -> store -> id).
           ' AND productId='.self::$db -> escape_string($product -> id)) -> num_rows;
    }
    
    
    /**
     * Возвращает количество продуктов в ассортименте магазина
     * 
     * @return type 
     */
    
    public function size()
    {
        $query = self::$db -> query(
            'SELECT COUNT(id) FROM Assortment
             WHERE storeId='.self::$db -> escape_string($this -> store -> id));
        
        $fetch = $query -> fetch_array();
        return $fetch[0];
    }
    
    
    /**
     * Возвращает true в случаи, если продукт использован хотя бы в одном из
     * магазинов.
     * 
     * @param Product $product
     * @return type 
     */
    
    public function isUsed(Product $product)
    {
        return (bool) self::_ls('id', array('productId' => $product -> id)) -> num_rows;
    }
    
    
    /**
     * Возвращает список(массив Assortment) всех продуктов, входящих в 
     * ассортимент магазина
     * 
     * @return array
     */
    
    public function ls()
    {
        return self::resultToRecords(
                self::_ls('*', array('storeId' => $this -> store-> id))
        );
    }
    
    
    /**
     * Расширенная версия ls, помимо стандартной информации возвращает
     * имена продуктов
     * 
     * @return type 
     */
    
    public function lsJoin()
    {

        $query = self::$db -> query('
                SELECT Assortment.*, Product.name as productName
                FROM Assortment
                LEFT JOIN Product ON Assortment.productId=Product.id
                WHERE Assortment.storeId='.self::$db -> escape_string($this -> store -> id));

        return self::resultToRecords($query);
    }
    
    
    /**
     * Формирует ассортимент, используя массив $productId => null,
     * и удаляет из ассортимента те продукты, которые в данном массиве
     * не определены.
     * 
     * @param array $productIds
     * @return boolean 
     */
    
    public function map(Array $productIds = null)
    {
        $existedRecords = $this -> ls();
        $products       = Product::ls();
        
        foreach ($products as $product)
        {
            if (isset($productIds[$product -> id]))
            {
                $isExisted = false;
                
                foreach ($existedRecords as $record)
                {
                    if ($record -> productId == $product -> id)
                    {
                        $isExisted = true;
                        continue;
                    }
                }
                
                if ($isExisted && !$productIds[$product -> id])
                {
                    $this -> remove($product);
                }
                else if (!$isExisted && $productIds[$product -> id])
                {
                    $this -> add($product);
                }
            }
            else
            {
                $this -> remove($product);
            }
        }
        
        return true;
    }
    
    
    /**
     * Удаляет все продукты из ассортимента
     * 
     * @return type 
     */
    
    public function drop()
    {
        return self::$db -> query('DELETE FROM Assortment WHERE storeId='.
               self::$db -> escape_string($this -> store -> id));
    }
}


/**
 *  Описание таблицы в БД 
 */

Assortment::setTable('Assortment', array(
    'id'        => 'int',
    'storeId'   => 'int',
    'productId' => 'int'
));

