function openDeleteFieldModalDialog(field) {
    $('#deleteFieldModalHeader').text('Czy na pewno chcesz usunąć działkę: ' + field.fieldNumber + '?');
    $('#deleteFieldBtn').unbind();
    $('#deleteFieldBtn').click(function () {
        deleteField(field.id);
    });
    $('#deleteFieldModal').modal({});
}

function deleteField(fieldId) {
    $.ajax({
        url: "../app/service/rest/field/field.php/" + fieldId,
        type: "DELETE",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                window.location.href = "/";
            }
        }
    });
}