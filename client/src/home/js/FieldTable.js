var maxRowNumber = 20;
var allVisiblesRows;
var maxSites = 1;
var currentPage = 0;

function getFieldsTableRowsInfo(site) {
    var $table = $('#homeTableActiveSeasonData');
    $rows = $table.find('tbody  tr:visible');
    var startNumber = maxRowNumber * site;
    var stopNumber = maxRowNumber * (site + 1);
    if (stopNumber > allVisiblesRows.length) {
        stopNumber = allVisiblesRows.length;
    }
    $('#fieldsTableShwonRowInfo').text('Wyświetlane ' + startNumber + ' do ' + stopNumber + ' z ' + allVisiblesRows.length);
    $('#fieldsHomeTableSiteInfo').text(' ' + (currentPage + 1) + ' ');
}

function generateFieldsTable(restUrl) {
    $('#homeTableLoadIcon').show();
    $.ajax({
        url: restUrl,
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                $('#homeTableLoadIcon').hide();
                drawTable(homeData);
                $("#homeTableActiveSeasonData").tablesorter({
                        headers: {
                            6: {sorter: false},
                            7: {sorter: false}
                        }
                    }
                );
                setCurrentVisbleRowsLength();
                show(0, maxRowNumber, 0); // show first
            }
        }
    });
}


function drawTable(data) {
    $("#homeTableActiveSeasonData").append('<tbody />');
    for (var i = 0; i < data.length; i++) {
        drawRow(data[i]);
    }
}


function drawRow(rowData) {
    displaySeasonId = rowData.seasonId;
    var row = $("<tr />")
    $("#homeTableActiveSeasonData").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
    row.append($("<td> " + rowData.fieldNumber + "</td>"));
    row.append($("<td>" + rowData.description + "</td>"));
    row.append($("<td>" + rowData.plant + "</td>"));
    row.append($("<td>" + rowData.varietes + "</td>"));
    row.append($("<td>" + rowData.ha + "</td>"));
    row.append($("<td>" + rowData.operationsNumber + "</td>"));
    row.append($("<td>  " +
        "<i onclick='openAdOperationModalDialog(" + JSON.stringify(rowData) + ")' class='fa fa-plus' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Dodaj nowy zabieg'/>" +
        "<i onclick='openFieldEditModalDialog(" + JSON.stringify(rowData) + ")' class='fa fa-edit' style='font-size:20px; cursor:pointer; margin-right: 5px' data-toggle='tooltip'  title='Edytuj'/>" +
        "<i onclick='openDeleteFieldModalDialog(" + JSON.stringify(rowData) + ");' class='fa fa-trash-o' style='font-size:20px; cursor:pointer;' data-toggle='tooltip'  title='Usuń'/>" +
        "</td>"));
    row.append($("<td>  <i onclick='getFieldDetails(" + rowData.id + ")' class='fa fa-arrow-circle-o-right' style='font-size:28px; cursor:pointer;' data-toggle='tooltip'  title='Szczegóły'/></td>"));
}


function getFieldDetails(fieldId) {
    window.location.href = '/nr/client/public/field#' + fieldId;
}


function fieldTableHeaderClick($ascIcon, $descIcon, $sortIcon) {
    if ($($sortIcon).is(":visible")) {
        hideAllHeaderSort();
        $($ascIcon).show();
    } else if ($($ascIcon).is(":visible")) {
        hideAllHeaderSort();
        $($descIcon).show();
    } else {
        hideAllHeaderSort();
        $($ascIcon).show();
    }
    $($sortIcon).hide();
}

function hideAllHeaderSort() {
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


function searchFieldsTable() {
    $("#search").on("keyup", function () {
        var value = $(this).val().toLocaleLowerCase();
        $("#homeTableActiveSeasonData tr").each(function (index) {
            if (index !== 0) {
                $row = $(this);
                var fieldNr = $row.find("td:nth-child(1)").text();
                var fieldDescription = $row.find("td:nth-child(2)").text();
                var fieldPlant = $row.find("td:nth-child(3)").text();
                var fieldVariates = $row.find("td:nth-child(4)").text();
                console.log(fieldDescription.indexOf(value) >= 0);
                if (fieldDescription.toLocaleLowerCase().indexOf(value) >= 0 || fieldNr.toLocaleLowerCase().indexOf(value) >= 0 || fieldPlant.toLocaleLowerCase().indexOf(value) >= 0 || fieldVariates.toLocaleLowerCase().indexOf(value) >= 0) {
                    $row.show();
                }
                else {
                    $row.hide();
                }
            }
        });
        currentPage = 0;
        setCurrentVisbleRowsLength();
        show(0, maxRowNumber);
    });
}

function show(min, max) {
    min = min ? min - 1 : 0;
    max = max ? max : allVisiblesRows.length;
    allVisiblesRows.hide().slice(min, max).show();
    getFieldsTableRowsInfo(currentPage);
}

function setCurrentVisbleRowsLength() {
    var $table = $('#homeTableActiveSeasonData');
    $rows = $table.find('tbody  tr:visible');
    allVisiblesRows = $rows;
    maxSites = Math.ceil(allVisiblesRows.length / maxRowNumber);
}

function goBack() {
    if (currentPage != 0) {
        currentPage--;
        show(currentPage * maxRowNumber, (currentPage + 1) * maxRowNumber);
    }
}

function goNext() {
    if (maxSites != currentPage + 1) {
        currentPage++;
        show(currentPage * maxRowNumber, (currentPage + 1) * maxRowNumber);
    }
}
