<script>
// Добавление пользователя в таблицу
function add(id, name, type)
{
    var tr = $('<tr id="person_'+id+'">');
        tr.id = "person_"+id;
        tr.append($('<td>').text(id));
        tr.append($('<td>').text(type));
        tr.append($('<td>').text(name));
        tr.append($('<td><div onclick="remove('+id+')" class="button icon-remove"></div></td>'));
        
    $('#persons').append(tr);
}

// Удаление
function remove(id)
{
    $.get(
        "/Person/Remove/", 
        { id:id },
        function(data)
        {
            if (data.error == undefined)
            {
                $('#person_'+id).remove();
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
        
        $.post(
            "/Person/Add/", 
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
    

    
    // Сохранение корзины
    $('#basketForm').submit(function()
    {
        if ($('#personsSelect').val() == 0)
        {
            alert('Нечего сохранять');
            return false;
        }
        
        $('#basketForm .ajax-loader').css('visibility', 'visible');
        
        $.post
        (
            "/Basket/Save/", 
            $('#basketForm').serialize(), 
            function(data)
                {
                    $('#basketForm .ajax-loader').css('visibility', 'hidden');
                    
                    if (data.error != undefined)
                    {
                        alert(data.error);
                    }
                    
                    $('#personsSelect').change();
                },
                "json"
        );
        
        return false;
    });
});


</script>


<div class="tab" id="tab1">
    <div class="header">
        <h1>Список покупателей</h1>
        <div class="icon-chevron-down"></div>            
    </div>
    
    <div class="body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td style="width:50px;">id</td>
                    <td style="width:200px;">Взрослый/ветеран</td>
                    <td>Имя человека</td>
                    <td style="width:20px;">-</td>
                </tr>
            </thead>
            <tbody id="persons"><?php
                foreach ($this -> persons as $person)
                {
                    echo '<tr id="person_',htmlspecialchars($person -> id),'">',
                        '<td>',htmlspecialchars($person -> id),'</td>',
                        '<td>',htmlspecialchars($person::explainType()),'</td>',
                        '<td class="name">',htmlspecialchars($person -> name),'</td>',
                        '<td><div onclick="remove(',htmlspecialchars($person -> id),
                            ')" class="button icon-remove"></div></td>',
                        '</tr>';
                }
            ?></tbody>
        </table>
    </div>
</div>

<div class="tab" id="tab2">
    <div class="header">
        <h1>Добавить информацию о покупателе</h1>
        <div class="icon-chevron-down"></div>
    </div>

    <div class="body">
        <form id="addForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">Имя</label>
                <div class="controls">
                    <input type="text" class="input-xsmall" name="name"/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Взрослый/ветеран</label>
                <div class="controls">
                    <select class="input-xsmall" name="type">
                        <option value="adult"><?php echo htmlspecialchars(PersonAdult::explainType()); ?></option>
                        <option value="veteran"><?php echo htmlspecialchars(PersonVeteran::explainType()); ?></option>
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