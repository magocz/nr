function openDeleteOperationModalDialog(operation) {
    $('#deleteFieldModalHeader').text('Czy na pewno chcesz usunąć operację z dnia: ' + operation.date);
    $('#deleteFieldBtn').unbind();
    $('#deleteFieldBtn').click(function () {
        deleteOperation(operation);
    });
    $('#deleteFieldModal').modal({});
}

function deleteOperation(operation) {
    $.ajax({
        url: "../app/service/rest/operation/operation.php/" + operation.id,
        type: "DELETE",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(operation),
        statusCode: {
            200: function () {
                reloadField();
            }
        }
    });
}