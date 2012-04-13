<script>
// Добавление продукта в таблицу
function add(id, name)
{
    var tr = $('<tr id="product_'+id+'">');
        tr.id = "product_"+id;
        tr.append($('<td>').text(id));
        tr.append($('<td>').text(name));
        tr.append($('<td><div onclick="remove('+id+')" class="button icon-remove"></div></td>'));
        
    $('#products').append(tr);
}

// Удаление
function remove(id)
{
    $.get(
        "/Product/Remove/", 
        { id:id },
        function(data)
        {
            if (data.error == undefined)
            {
                $('#product_'+id).remove();
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
    makeTabs('tab', 4, 1); 
    
    
    // Ajax-запрос на добавления информации о продукте
    $("#addForm").submit(function()
    {
        $('#addForm .ajax-loader').css('visibility', 'visible');
         
        $.post(
            "/Product/Add/", 
            $('#addForm').serialize(), 
            function(data)
                {
                    $('#addForm .ajax-loader').css('visibility', 'hidden');
                    
                    if (data.error == undefined)
                    {
                        add(data.id, data.name);
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
});
</script>

<div class="tab" id="tab1">
    <div class="header">
        <h1>Продукты</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td style="width:50px;">id</td>
                    <td>Название продукта</td>
                    <td style="width:20px;">-</td>
                </tr>
            </thead>
            <tbody id="products"><?php
                foreach ($this -> products as $product)
                {
                    echo '<tr id="product_',htmlspecialchars($product -> id),'">',
                        '<td>',htmlspecialchars($product -> id),'</td>',
                        '<td>',htmlspecialchars($product -> name),'</td>',
                        '<td><div onclick="remove(',htmlspecialchars($product -> id),
                            ')" class="button icon-remove"></div></td>',
                        '</tr>';
                }
            ?></tbody>
        </table>
    </div>
</div>

<div class="tab" id="tab2">
    <div class="header">
        <h1>Добавить новый продукт</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <form id="addForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Наименование</label>
                <div class="controls">
                    <input type="text" class="input-xsmall" name="name"/>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Добавить</button>
                <div class="ajax-loader"></div>
            </div>
        </form>
    </div>
</div>