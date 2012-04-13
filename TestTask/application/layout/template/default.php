<!doctype html>
<html>
    <head>
        <title>Тестовое задание</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="/resources/css/index.css"/>
        <script src="/resources/jquery/jquery-1.7.js"></script>
        <script src="/resources/js/index.js"></script>
    </head>
    
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner"><div class="container">
                <ul class="nav"><?php
                    $links   = array();
                    $links[] = array('Проект', 'Index');
                    $links[] = array('Продукты', 'Product');
                    $links[] = array('Магазины', 'Store');
                    $links[] = array('Покупатели', 'Person');
                    $links[] = array('Очереди', 'Queue');
                    $links[] = array('Отчет', 'Report');

                    $request = Request::getInstance();

                    foreach ($links as $link)
                    {
                        echo '<li ';
                        if ($request -> getControllerName() == $link[1])
                            echo 'class="active"';

                        echo '><a href="/',htmlspecialchars($link[1]),'/">',
                        htmlspecialchars($link[0]),'</a></li>';
                    }
                ?></ul>
            </div></div>
        </div>
        
        <div class="page">
            <?php if (isset($this -> _pageContent)) echo $this -> _pageContent ?> 
        </div>
    </body>
</html>