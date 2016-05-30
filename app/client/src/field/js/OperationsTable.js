function drawFieldOperationsTable(data) {
    $("#fieldOperationsTable").append('<tbody />');
    for (var i = 0; i < data.length; i++) {
        drawFieldOperationsRow(data[i]);
    }
}


function drawFieldOperationsRow(rowData) {
    var row = $("<tr />")
    $("#fieldOperationsTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
    row.append($("<td> " + rowData.date + "</td>"));
    row.append($("<td>" + rowData.meansName + "</td>"));
    row.append($("<td>" + (rowData.meansType == 'fertilizer' ? 'Nawóz' : 'Ochorna roślin' ) + "</td>"));
    row.append($("<td>" + (rowData.meansDoseInLProHa == 0 ? '-' : rowData.meansDoseInLProHa ) + "</td>"));
    row.append($("<td>" + (rowData.meansDoseInKgProHa == 0 ? '-' : rowData.meansDoseInKgProHa) + "</td>"));
    row.append($("<td>  " +
       // "<i onclick='openDetailsOperationModal(" + rowData + ");' class='fa fa-info' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Szegóły'/>" +
        "<i onclick='openEditOperationModalDialog(" + JSON.stringify(rowData) + ")' class='fa fa-edit' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Edytuj'/>" +
        "<i onclick='openDeleteOperationModalDialog(" + JSON.stringify(rowData) + ");' class='fa fa-trash-o' style='font-size:20px; cursor:pointer;' data-toggle='tooltip'  title='Usuń'/>" +
        "</td>"));
}


function fieldOperationsTableHeaderClick($ascIcon, $descIcon, $sortIcon) {
    if ($($sortIcon).is(":visible")) {
        hideAllOperationsTableHeaderSort();
        $($ascIcon).show();
    } else if ($($ascIcon).is(":visible")) {
        hideAllOperationsTableHeaderSort();
        $($descIcon).show();
    } else {
        hideAllOperationsTableHeaderSort();
        $($ascIcon).show();
    }
    $($sortIcon).hide();
}

function hideAllOperationsTableHeaderSort() {
    $("#fieldNrSortAsc").hide();
    $("#fieldNrSortDsc").hide();
    $("#fieldDescriptionSortAsc").hide();
    $("#fieldDescriptionSortDsc").hide();
    $("#fieldPlantSortAsc").hide();
    $("#fieldPlantSortDsc").hide();
    $("#fieldVarietesSortAsc").hide();
    $("#fieldVarietesSortDsc").hide();
    $("#fieldSizeSortAsc").hide();
    $("#fieldSizeSortDsc").hide();
    $("#fieldOperationsSortAsc").hide();
    $("#fieldOperationsSortDsc").hide();

    $("#fieldNrSort").show();
    $("#fieldPlantSort").show();
    $("#fieldVarietesrSort").show();
    $("#fieldSizeSort").show();
    $("#fieldOperationsSort").show();
    $("#fieldDescriptionSort").show();
}
