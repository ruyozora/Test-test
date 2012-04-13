<?php
/*
 *  [ Basket ]
 * 
 *  Реализация корзины, входит как свойство в классе Person.
 *  Реализует индивидуальную корзину конкретного покупателя для конкретного 
 *  магазина.  
 */

class Basket extends ActiveRecord
{
    protected $person;
    protected $personId;
    protected $productId;

    
    /**
     * Автоматически добавляет связь между корзиной и конкретным покупателем
     * 
     * @param Person $person 
     */
    
    public function __construct (Person $person = null)
    {
        if (!is_null($person)) $this -> person = $person;
    }

    
    /**
     * Установить связь между корзиной и конкретным покупателем
     * 
     * @param Person $person 
     */
    
    protected function setPerson(Person $person)
    {
        $this -> person = $person;
    }
    
    
    /**
     * Добавить продукт в покупательскую корзину
     * 
     * @param Product $product
     * @return type
     * @throws Exception 
     */
    
    public function add(Product $product)
    {
        if (!$this -> person)
            throw new Exception('No person selected');
                
        if ($this -> isUsed($product))
            throw new Exception('Этот продукт уже есть в корзине покупателя');
        
        $this -> personId   = $this -> person -> id;        
        $this -> productId =  $product -> id;
        
        return self::_insert();
    }
    
    
    /**
     * Удалить продукт из покупательской корзины в случаи, если он там 
     * присутствует
     * 
     * @param Product $product
     * @return boolean
     * @throws Exception 
     */
    
    public function remove(Product $product)
    {
        if (!$this -> person)
            throw new Exception('No person selected');        

        $this -> personId   = $this -> person -> id;
        $this -> productId = $product -> id;

        self::$db -> query(
           'DELETE FROM Basket WHERE '.
           'personId='.self::$db -> escape_string($this -> personId).
           ' AND productId='.self::$db -> escape_string($this -> productId));
        
        return true;
    }
    
    
    /**
     * Возвращает true в случаи, если данный покупатель уже добавлял себе данный
     * продукт в свою корзину.
     * 
     * @param Product $product
     * @return type 
     */
    
    public function isUsed(Product $product)
    {
        return (bool) self::_ls('id', array(
            'productId' => $product -> id,
            'personId' => $this -> person -> id)
        ) -> num_rows;
    }
    
    
    /**
     * Возвращает список id продуктов, находящихся в корзине покупателя
     * 
     * @return type 
     */
    
    public function ls()
    {
        return self::resultToRecords(
                self::_ls('*', array('personId' => $this -> person-> id))
        );
    }
    
    
    /**
     * Формирует корзину, используя массив $productId => null,
     * и удаляет из корзины те продукты, которые в данном массиве
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
     * Удалить все продукты из корзины покупателя
     * 
     * @return type 
     */
    
    public function drop()
    {
        return self::$db -> query('DELETE FROM Basket WHERE personId='.
               self::$db -> escape_string($this -> person -> id));
    }
}


/**
 *  Описание таблицы в БД 
 */

Basket::setTable('Basket', array(
    'id'        => 'int',
    'personId'  => 'int',
    'productId' => 'int'
));