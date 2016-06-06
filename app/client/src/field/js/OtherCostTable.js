function drawOtherCostTable(data) {
    $("#otherCostTable").append('<tbody />');
    for (var i = 0; i < data.length; i++) {
        drawOtherCostRow(data[i]);
    }
}


function drawOtherCostRow(rowData) {
    var row = $("<tr />")
    $("#otherCostTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
    row.append($("<td> " + rowData.date + "</td>"));
    row.append($("<td>" + rowData.comment + "</td>"));
    row.append($("<td>" + rowData.cost + "</td>"));
    row.append($("<td>  " +
        // "<i onclick='openDetailsOperationModal(" + rowData + ");' class='fa fa-info' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Szegóły'/>" +
        "<i onclick='openEditOtherCostModalDialog(" + JSON.stringify(rowData) + ")' class='fa fa-edit' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Edytuj'/>" +
        "<i onclick='openDeleteOtherCostModalDialog(" + JSON.stringify(rowData) + ");' class='fa fa-trash-o' style='font-size:20px; cursor:pointer;' data-toggle='tooltip'  title='Usuń'/>" +
        "</td>"));
}


function otherCostTableHeaderClick($ascIcon, $descIcon, $sortIcon) {
    if ($($sortIcon).is(":visible")) {
        hideOtherCostTableHeaderSort();
        $($ascIcon).show();
    } else if ($($ascIcon).is(":visible")) {
        hideOtherCostTableHeaderSort();
        $($descIcon).show();
    } else {
        hideOtherCostTableHeaderSort();
        $($ascIcon).show();
    }
    $($sortIcon).hide();
}

function hideOtherCostTableHeaderSort() {
    $("#otherCostDateSortAsc").hide();
    $("#otherCostDateSortDsc").hide();
    $("#otherCostCommentSortAsc").hide();
    $("#otherCostCommentSortDsc").hide();
    $("#otherCostSortAsc").hide();
    $("#otherCostSortDsc").hide();

    $("#otherCostDateSort").show();
    $("#otherCostCommentSort").show();
    $("#otherCostSort").show();
}
