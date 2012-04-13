/* 
    Document   : index.js
    Created on : 10.04.2012, 10:39:45
    Author     : anonymous
*/

function makeTabs(idPrefix, numTabs, show)
{
    var select = function(id)
    {
        var div = $('#'+idPrefix+id+' .header div');
        var body = $('#'+idPrefix+id+' .body');

        if (body.is(':hidden'))
        {
            div.attr('class', 'icon-chevron-up');
            body.slideDown('fast');           
        }
        else
        {
            div.attr('class', 'icon-chevron-down');
            body.hide();
        }
    }
    
    for (var i = 1; i <= numTabs; i++)
    {
        if (i != show) $('#'+idPrefix+i+' .body').hide();
        
        $('#'+idPrefix+i+' .header').click({id:i}, function(event)
        {
            select(event.data.id);
        });
    }
}
