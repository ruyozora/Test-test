<?php
/**
 *  [ Product ]
 * 
 *  Реализация продукта, товара.
 */

class Product extends ActiveRecord
{
    protected $name;
    protected $assortmentId; # костыль, не обращайте внимания

    
    /**
     * Добавляет информацию о продукте в базу данных.
     * 
     * @return type
     * @throws Exception 
     */
    
    public function add()
    {
        if (!strlen($this -> name))
            throw new Exception('Отсутствует наименование продукта');
        
        return $this -> _insert();
    }
    
    
    /**
     * Удаляет информацию о продукте, если это возможно.
     * 
     * @return type 
     */
    
    public function remove()
    {
        return self::sremove($this -> id);
    }
    
    
    /**
     * Статический вариант remove
     * 
     * @param type $id
     * @return type
     * @throws Exception 
     */
    
    public static function sremove($id)
    {
        $query = self::$db -> query(
            'SELECT id FROM Queue WHERE personId=ANY(
             SELECT personId FROM Basket '.
            'WHERE productId='.self::$db -> escape_string($id).');');
        
        if ($query -> num_rows > 0)
            throw new Exception('Невозможно удалить товар, пока один из покупателей стоит в очереди за ним');
        
        $query = self::$db -> query('DELETE FROM Assortment '.
                                    'WHERE productId='.self::$db -> escape_string($id));
        
        return self::_sremove($id);
    }
    
    
    /**
     * Возвращает объект данного класса, с информацией, полученный по id
     * 
     * @param type $id
     * @return \self 
     */
    public static function get($id)
    {
        $r = self::_get($id);
        $result = new self();
        $result -> setParams($r);
        return $result;
    }
    
    
    /**
     * Возвращает список всех существующих продуктов.
     * 
     * @return type 
     */
    
    public static function ls()
    {
        $query = 'SELECT Product.*, Assortment.id as assortmentId 
                  FROM Product LEFT JOIN  Assortment
                  ON Product.id = Assortment.id ';
        
        return self::resultToRecords(self::$db -> query($query));
    }
}


/**
 *  Описание таблицы в БД 
 */

Product::setTable('Product', array(
    'id'   => 'int',
    'name' => 'tinytext'
));