<script>
$(document).ready(function() 
{     
    // Добавление табов
    makeTabs('tab', 2, 1);
    
    // Добавление человека в очередь
    $("#addForm").submit(function()
    {
        if ($('#whoSelect').val() == 0 ||
            $('#whereSelect').val() == 0)
        {
            alert('Нечего сохранять');
            return false;;
        }
        
        $('#addForm .ajax-loader').css('visibility', 'visible');
         
        var todelete = $('#whoSelect').val();
         
        $.post(
            "/Queue/Add/", 
            $('#addForm').serialize(), 
            function(data)
                {
                    $('#addForm .ajax-loader').css('visibility', 'hidden');

                    if (data.error == undefined)
                    {
                        showQueue($('#whereSelect').val());
                        
                        $('#whoSelect').val(0);
                        $('#whereSelect').val(0);
                        $('#productsList').html('');
                        
                        $('#whoSelect option[value="'+todelete+'"]').remove();
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
    
    $('#storesSelect').change(function() {
        showQueue($('#storesSelect').val());
    });
    
    
    // Автозапрос заполненных чекбоксов
    $('#whereSelect').change(function()
    {
        var inputs = $('#productsList');

        $.get
        (
            '/Assortment/Ls/',
            {'storeId': $('#whereSelect').val()},
            function(data)
            {
                inputs.html('');
                
                for (var i in data)
                {
                    var checkbox = $('<input>')
                        .attr("type", "checkbox")
                        .attr("id", "product_"+data[i].productId)
                        .attr("name", "product_"+data[i].productId);
                        
                    var label = $('<label>')
                        .attr('class', 'checkbox')
                        .text(data[i].name)
                        .append(checkbox);
                        
                    inputs.append(label);
                }
            },
            'json'
        );
    });
});

// Показ очереди
function showQueue(id)
{
    $('#nullSelect').remove();
    $('#queue').html('');

    $.get
    (
        '/Queue/Ls/',
        {'storeId': id},
        function(data)
        {
            for (var i in data)
            {
                var row = data[i];

                var tr = $('<tr id="queue_'+row.id+'">');
                    tr.id = "queue_"+row.id;
                    tr.append($('<td>').text(row.id));
                    tr.append($('<td>').text(row.personName));
                    tr.append($('<td>').text(row.personPosition));
                    tr.append($('<td><div onclick="remove('+row.id+','+id+', '+row.personId+',\''+row.personName+'\')" class="button icon-remove"></div></td>'));

                $('#queue').append(tr);
            }
        },
        'json'
    );
}

// Удаление
function remove(id, storeId, personId, personName)
{
    $.get(
        "/Queue/Remove/", 
        { id:id, storeId:storeId, personId:personId },
        function(data)
        {
            if (data.error == undefined)
            {
                $('#queue_'+id).remove();
                $('#whoSelect').append($('<option>').val(personId).text(personName));
                showQueue($('#storesSelect').val());
            }
            else
            {
                alert(data.error);
            }
        },
        "json"
    );
}
</script>

<div class="tab" id="tab1">
    <div class="header">
        <h1>Очереди</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <form id="listForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Магазин</label>
                <div class="controls">
                    
                    <select id="storesSelect" class="input-xsmall" name="type">
                        <option value="0" id="nullSelect">-</option>
                        <?php
                        foreach ($this -> stores as $store)
                        {
                            echo '<option value="',htmlspecialchars($store -> id),
                                 '">', htmlspecialchars($store -> name),'</option>';
                        }
                    ?></select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Очередь</label>
                <div class="controls">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td style="width:50px;">id</td>
                                <td>Человек</td>
                                <td style="width:50px;">#N</td>
                                <td style="width:50px;">-</td>
                            </tr>
                        </thead>
                        <tbody id="queue">
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="tab" id="tab2">
    <div class="header">
        <h1>Добавить в очередь</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <form id="addForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Кого?</label>
                <div class="controls">
                    <select class="input-xsmall" id="whoSelect" name="personId">
                        <option value="0" id="whoNullSelect">-</option><?php
                        foreach ($this -> persons as $person)
                        {
                            if (!$person -> qId)
                            echo '<option value="',htmlspecialchars($person -> id),
                                 '">', htmlspecialchars($person -> name),'</option>';
                        }
                    ?></select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Куда?</label>
                <div class="controls">
                    
                    <select class="input-xsmall" id="whereSelect" name="storeId">
                        <option value="0" id="whereNullSelect">-</option><?php
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
                <div class="controls" id="productsList">-</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Добавить</button>
                <div class="ajax-loader"></div>
            </div>
        </form>
    </div>
</div>