#
#   [ scheme.sql ]
#
#   Создает необходимые для работы данного приложения таблицы в базе данных,
#   а также добавляет тестовые записи.
#
#   TODO:: расширить SQL-схему, добавить ограничители
#

CREATE TABLE Person
(
    id int auto_increment primary key,
    name tinytext,    # Для упрощения не стану рассписывать в три поля
    type tinytext     # Можно использовать перечисление здесь, но я еще не знаю
                      # насчет расширяемости данного способа
);


CREATE TABLE Store
(
    id int auto_increment primary key,
    name tinytext,
    type tinytext
);


CREATE TABLE Product
(
    id int auto_increment primary key,
    name tinytext
);


CREATE TABLE Assortment
(
    id int auto_increment primary key,
    storeId int,
    productId int,

    FOREIGN KEY (storeId)    REFERENCES Store(id),
    FOREIGN KEY (productId) REFERENCES Product(id)
);


CREATE TABLE Basket
(
    id int auto_increment primary key,
    personId int,
    productId int,

    FOREIGN KEY (personId)  REFERENCES Person(id),
    FOREIGN KEY (productId) REFERENCES Product(id)
);


CREATE TABLE Queue
(
    id int auto_increment primary key,
    storeId int,
    personId int,
    personPosition int,

    FOREIGN KEY (storeId)    REFERENCES Store(id),
    FOREIGN KEY (personId)  REFERENCES Person(id)
);


#
#   [ Report view ]
#
#   Mysql не умеет в подзапросы в представлениях.
#

# CREATE VIEW Report AS
#    SELECT Product.name as productName, S.* FROM
#    (
#        SELECT Basket.productId as bpId, P.* FROM 
#            (
#            SELECT Q.*, Store.name AS storeName FROM (
#                    SELECT 
#                        Queue.storeId, Queue.personId, 
#                        Person.name, Person.type, Queue.personPosition
#                    FROM Queue
#                    LEFT JOIN Person ON Queue.personId=Person.id
#                    ORDER BY Queue.personPosition)
#                AS Q
#                LEFT JOIN Store ON Q.storeId = Store.id
#            ) AS P
#        LEFT JOIN Basket ON P.personId = Basket.personId
#    ) AS S
#    LEFT JOIN Product ON Product.id=S.bpId;