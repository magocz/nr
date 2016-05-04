$(function () {
    $('#panel').hide(10000);
    $(document.createElement('table'));

    function createProviderFormFields(id, labelText, tooltip, regex) {
        var tr = '<tr>';
        // create a new textInputBox
        var textInputBox = '<input type="text" id="' + id + '" name="' + id + '" title="' + tooltip + '" />';
        // create a new Label Text
        tr += '<td>' + labelText + '</td>';
        tr += '<td>' + textInputBox + '</td>';
        tr += '</tr>';
        return tr;
    };

    $( document ).ready(function() {
        $("#providersFormElementsTable").html("<tr><td>Nickname</td><td><input type='text' id='nickname' name='nickname'></td></tr><tr><td>CA Number</td><td><input type='text' id='account' name='account'></td></tr>");
    });


});

