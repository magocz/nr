function openDeleteOtherCostModalDialog(otherCost) {
    $('#otherCostModalHeader').text('Czy na pewno chcesz usunąć koszt z dnia: ' + otherCost.date);
    $('#otherCostBtn').unbind();
    $('#otherCostBtn').click(function () {
        deleteOtherCost(otherCost);
    });
    $('#otherCostModal').modal({});
}

function deleteOtherCost(otherCost) {
    $.ajax({
        url: "../app/service/rest/other-cost/other-cost.php/" + otherCost.id,
        type: "DELETE",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        statusCode: {
            200: function () {
                reloadField();
            }
        }
    });
}