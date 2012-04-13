<script>
// Добавление пользователя в таблицу
function add(id, name, type)
{
    var tr = $('<tr id="store_'+id+'">');
        tr.id = "store_"+id;
        tr.append($('<td>').text(id));
        tr.append($('<td>').text(type));
        tr.append($('<td>').text(name));
        tr.append($('<td><div onclick="remove('+id+')" class="button icon-remove"></div></td>'));
        
    $('#stores').append(tr);
    $('#storesSelect').append($('<option>').val(id).text(name));
}

// Удаление
function remove(id)
{
    $.get(
        "/Store/Remove/", 
        { id:id },
        function(data)
        {
            if (data.error == undefined)
            {
                $('#storesSelect option[value="'+id+'"]').remove();
                $('#store_'+id).remove();
            }
            else
            {
                alert(data.error);
            }
        },
        "json"
    );
}

$(document).ready(function() 
{ 
    // Добавление табов
    makeTabs('tab', 3, 1); 
    
    
    // Ajax-запрос на добавления информации о человека
    $("#addForm").submit(function()
    {
        $('#addForm .ajax-loader').css('visibility', 'visible');
        
        $.post
        (
            "/Store/Add/", 
            $('#addForm').serialize(), 
            function(data)
                {
                    $('#addForm .ajax-loader').css('visibility', 'hidden');
                    
                    if (data.error == undefined)
                    {
                        add(data.id, data.name, data.expl);
                    }
                    else
                    {
                        alert(data.error);
                    }
                },
                "json"
        );
        
        return false;
    });
    
    // Автозапрос заполненных чекбоксов
    $('#storesSelect').change(function()
    {
        $('#nullSelect').remove();
        
        $.get
        (
            "/Assortment/Ls/",
            { "storeId":$('#storesSelect').val() },
            function(data)
            {
                $.each($('#productsList input'), function()
                {
                    $('#'+this.id).attr('checked', null);
                });
                
                for (var i in data)
                {
                    $('#product_'+data[i].productId).attr('checked', 'true');
                }
            },
            "json"
        );
    });
    
    // Сохранение ассортимента
    $('#assortmentForm').submit(function()
    {
        if ($('#storesSelect').val() == 0)
        {
            alert('Нечего сохранять');
            return false;
        }
        
        $('#assortmentForm .ajax-loader').css('visibility', 'visible');
        
        $.post
        (
            "/Assortment/Save/", 
            $('#assortmentForm').serialize(), 
            function(data)
                {
                    $('#assortmentForm .ajax-loader').css('visibility', 'hidden');
                    
                    if (data.error != undefined)
                    {
                        alert(data.error);
                    }
                    
                    $('#storesSelect').change();
                },
                "json"
        );
        
        return false;
    });
});
</script>

<div class="tab" id="tab1">
    <div class="header">
        <h1>Магазины</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td style="width:50px;">id</td>
                    <td style="width:200px;">Тип</td>
                    <td>Название</td>
                    <td style="width:20px;"></td>
                </tr>
            </thead>
            <tbody id="stores"><?php
                foreach ($this -> stores as $store)
                {
                    echo '<tr id="store_',htmlspecialchars($store -> id),'">',
                        '<td>',htmlspecialchars($store -> id),'</td>',
                        '<td>',htmlspecialchars($store::explainType()),'</td>',
                        '<td class="name">',htmlspecialchars($store -> name),'</td>',
                        '<td><div onclick="remove(',htmlspecialchars($store -> id),
                            ')" class="button icon-remove"></div></td>',
                        '</tr>';
                }
            ?></tbody>
        </table>
    </div>
</div>

<div class="tab" id="tab2">
    <div class="header">
        <h1>Добавить информацию магазине</h1>
        <div class="icon-chevron-down"></div>
    </div>

    <div class="body">
        <form id="addForm" action="/Store/" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Имя</label>
                <div class="controls">
                    <input type="text" class="input-xsmall" name="name"/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Тип магазина</label>
                <div class="controls">
                    <select class="input-xsmall" name="type">
                        <option value="grocery"><?php echo htmlspecialchars(StoreGrocery::explainType()); ?></option>
                        <option value="hardware"><?php echo htmlspecialchars(StoreHardware::explainType()); ?></option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Добавить</button>
                <div class="ajax-loader"></div>
            </div>
        </form>
    </div>
</div>

<div class="tab" id="tab3">
    <div class="header">
        <h1>Ассортимент</h1>
        <div class="icon-chevron-down"></div>
    </div>
    <div class="body">
        <form id="assortmentForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Магазин</label>
                <div class="controls">
                    
                    <select id="storesSelect" class="input-xsmall" name="storeId">
                        <option value="0" id="nullSelect">-</option><?php
                        foreach ($this -> stores as $store)
                        {
                            echo '<option value="',htmlspecialchars($store -> id),
                                 '">', htmlspecialchars($store -> name),'</option>';
                        }
                    ?></select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Продукты</label>
                <div class="controls" id="productsList"><?php
                    foreach ($this -> products as $product)
                    {
                        echo '<label class="checkbox"',
                        '><input name="product_',  htmlspecialchars($product -> id),
                        '" type="checkbox" id="product_',
                        htmlspecialchars($product -> id),'"> ', 
                        htmlspecialchars($product -> name) ,
                        '</label>';
                    }
                ?></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <div class="ajax-loader"></div>
            </div>
        </form>
    </div>
</div>