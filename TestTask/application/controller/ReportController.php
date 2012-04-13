<?php
class ReportController extends Controller
{
    public function Index()
    {
        $db = Application::getInstance() -> mysqli;
        $report = array();
        
        /**
         *  Вы ведь помните собачку на главной странице этого проекта, да? 
         *  Возможно, есть более лучшие решения данной задачи.
         */
        
        $query = $db -> query
        ('
            SELECT 
            GROUP_CONCAT(DISTINCT productName SEPARATOR ", ") as products,
            T.* FROM (
                SELECT Product.name as productName, S.* FROM
                (
                    SELECT Basket.productId as bpId, P.* FROM 
                        (
                        SELECT Q.*, Store.name AS storeName FROM (
                                SELECT 
                                    Queue.storeId, Queue.personId, Queue.personPosition as queuePosition,
                                    Person.name as personName, Person.type as PersonType
                                FROM Queue
                                LEFT JOIN Person ON Queue.personId=Person.id
                                ORDER BY Queue.personPosition)
                            AS Q
                            LEFT JOIN Store ON Q.storeId = Store.id
                        ) AS P
                    LEFT JOIN Basket ON P.personId = Basket.personId
                ) AS S
                LEFT JOIN Product ON Product.id=S.bpId
            ) AS T 
            GROUP BY T.personId
            ORDER BY T.storeId, T.queuePosition
        ');
        
        if (!$query)
            throw new Exception('SQL Error: '.$db -> error);
        
        while ($fetch = $query -> fetch_assoc())
        {
            $report[] = array(
                       'store' => $fetch['storeName'],
                      'person' => $fetch['personName'],
                        'type' => $fetch['PersonType'],
                    'products' => $fetch['products'],
                    'position' => $fetch['queuePosition']
                );
        }
        
        return array('report' => $report);
    }
}