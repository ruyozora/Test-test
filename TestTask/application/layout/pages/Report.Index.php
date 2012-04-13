<h1>Отчет</h1>

<br/>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>Магазин</td>
            <td>Человек</td>
            <td>Взрослый/ветеран</td>
            <td>Товар</td>
            <td>Место в очереди</td>
        </tr>
    </thead>
    <tbody><?php
        foreach ($this -> report as $row)
        {
            echo '<tr>',
                 '<td>', htmlspecialchars($row['store']), '</td>',
                 '<td>', htmlspecialchars($row['person']), '</td>',
                 '<td>', htmlspecialchars($row['type']), '</td>',
                 '<td>', htmlspecialchars($row['products']), '</td>',
                 '<td>', htmlspecialchars($row['position']), '</td>',
                 '</tr>';
        }
    ?></tbody>
</table>

<pre style='color:#000000;background:#ffffff;'><span style='color:#7f0055; font-weight:bold; '>SELECT</span> 
GROUP_CONCAT(<span style='color:#7f0055; font-weight:bold; '>DISTINCT</span> productName SEPARATOR <span style='color:#2a00ff; '>", "</span>) <span style='color:#7f0055; font-weight:bold; '>as</span> products,
T.* <span style='color:#7f0055; font-weight:bold; '>FROM</span> (
    <span style='color:#7f0055; font-weight:bold; '>SELECT</span> Product.name <span style='color:#7f0055; font-weight:bold; '>as</span> productName, <span style='color:#7f0055; font-weight:bold; '>S</span>.* <span style='color:#7f0055; font-weight:bold; '>FROM</span>
    (
        <span style='color:#7f0055; font-weight:bold; '>SELECT</span> Basket.productId <span style='color:#7f0055; font-weight:bold; '>as</span> bpId, P.* <span style='color:#7f0055; font-weight:bold; '>FROM</span> 
            (
            <span style='color:#7f0055; font-weight:bold; '>SELECT</span> Q.*, <span style='color:#7f0055; font-weight:bold; '>Store</span>.name <span style='color:#7f0055; font-weight:bold; '>AS</span> storeName <span style='color:#7f0055; font-weight:bold; '>FROM</span> (
                    <span style='color:#7f0055; font-weight:bold; '>SELECT</span> 
                        <span style='color:#7f0055; font-weight:bold; '>Queue</span>.storeId, <span style='color:#7f0055; font-weight:bold; '>Queue</span>.personId, <span style='color:#7f0055; font-weight:bold; '>Queue</span>.personPosition <span style='color:#7f0055; font-weight:bold; '>as</span> queuePosition,
                        Person.name <span style='color:#7f0055; font-weight:bold; '>as</span> personName, Person.<span style='color:#7f0055; font-weight:bold; '>type</span> <span style='color:#7f0055; font-weight:bold; '>as</span> PersonType
                    <span style='color:#7f0055; font-weight:bold; '>FROM</span> <span style='color:#7f0055; font-weight:bold; '>Queue</span>
                    <span style='color:#7f0055; font-weight:bold; '>LEFT</span> <span style='color:#7f0055; font-weight:bold; '>JOIN</span> Person <span style='color:#7f0055; font-weight:bold; '>ON</span> <span style='color:#7f0055; font-weight:bold; '>Queue</span>.personId=Person.<span style='color:#7f0055; font-weight:bold; '>id</span>
                    <span style='color:#7f0055; font-weight:bold; '>ORDER</span> <span style='color:#7f0055; font-weight:bold; '>BY</span> <span style='color:#7f0055; font-weight:bold; '>Queue</span>.personPosition)
                <span style='color:#7f0055; font-weight:bold; '>AS</span> Q
                <span style='color:#7f0055; font-weight:bold; '>LEFT</span> <span style='color:#7f0055; font-weight:bold; '>JOIN</span> <span style='color:#7f0055; font-weight:bold; '>Store</span> <span style='color:#7f0055; font-weight:bold; '>ON</span> Q.storeId = <span style='color:#7f0055; font-weight:bold; '>Store</span>.<span style='color:#7f0055; font-weight:bold; '>id</span>
            ) <span style='color:#7f0055; font-weight:bold; '>AS</span> P
        <span style='color:#7f0055; font-weight:bold; '>LEFT</span> <span style='color:#7f0055; font-weight:bold; '>JOIN</span> Basket <span style='color:#7f0055; font-weight:bold; '>ON</span> P.personId = Basket.personId
    ) <span style='color:#7f0055; font-weight:bold; '>AS</span> <span style='color:#7f0055; font-weight:bold; '>S</span>
    <span style='color:#7f0055; font-weight:bold; '>LEFT</span> <span style='color:#7f0055; font-weight:bold; '>JOIN</span> Product <span style='color:#7f0055; font-weight:bold; '>ON</span> Product.<span style='color:#7f0055; font-weight:bold; '>id</span>=<span style='color:#7f0055; font-weight:bold; '>S</span>.bpId
) <span style='color:#7f0055; font-weight:bold; '>AS</span> T 
<span style='color:#7f0055; font-weight:bold; '>GROUP</span> <span style='color:#7f0055; font-weight:bold; '>BY</span> T.personId
<span style='color:#7f0055; font-weight:bold; '>ORDER</span> <span style='color:#7f0055; font-weight:bold; '>BY</span> T.storeId, T.queuePosition
</pre>