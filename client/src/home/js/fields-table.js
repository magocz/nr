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
    $.ajax({
        url: restUrl,
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                drawTable(homeData.data);
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
    var row = $("<tr />")
    $("#homeTableActiveSeasonData").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
    row.append($("<td> " + rowData.fieldNumber + "</td>"));
    row.append($("<td>" + rowData.description + "</td>"));
    row.append($("<td>" + rowData.plant + "</td>"));
    row.append($("<td>" + rowData.varietes + "</td>"));
    row.append($("<td>" + rowData.ha + "</td>"));
    row.append($("<td>" + rowData.operationsNumber + "</td>"));
    var field = [rowData.id, rowData.description, rowData.plant];
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


function openAdOperationModalDialog(field) {
    $('#addOperationModalHeader').text('Dodaj zabieg do działki: ' + field.fieldNumber);
    $('#operationDate').datepicker();
    $('#operationDate').datepicker('setDate', new Date());
    configModalToDispalyCallender();
    $('#addOperationBtn').unbind();
    $('#addOperationBtn').click(function () {
        addOperation(field);
    });

    $('#addOperationdModal').modal();
}

function addOperation(field){
    console.log(field.id);
    console.log(field.seasonId);
}

function configModalToDispalyCallender() {
    var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };
    try {
        $confModal.on('hidden', function () {
            $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
        });
        $confModal.modal({backdrop: false});
    }
    catch (error) {
        if (error.name != 'ReferenceError')
            throw error;
    }
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

function openFieldEditModalDialog(field) {
    $('#editFieldModalHeader').text('Edycja działki: ' + field.fieldNumber);
    fillEditableFields(field);
    $('#updateFieldBtn').unbind();
    $('#updateFieldBtn').click(function () {
        updateField(field);
    });
    $('#editFieldModal').modal({});
}

function openDeleteFieldModalDialog(field) {
    $('#deleteFieldModalHeader').text('Czy na pewno chcesz usunąć pole: ' + field.fieldNumber);
    $('#deleteFieldBtn').unbind();
    $('#deleteFieldBtn').click(function () {
        deleteField(field.id);
    });
    $('#deleteFieldModal').modal({});
}

function deleteField(fieldId) {
    $.ajax({
        url: "../../service/field/rest/field.php/" + fieldId,
        type: "DELETE",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                loadHomeData();
            }
        }
    });
}

function updateField(field) {
    if (checkRequiredFieldInEditFieldModal()) {
        fillJSONEditableFields(field);
        $.ajax({
            url: "../../service/field/rest/field.php/" + field.id,
            type: "POST",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(fillJSONEditableFields(field)),
            statusCode: {
                200: function (homeData) {
                    loadHomeData();
                    clearAllFieldEditModalInputs();
                    $('#editFieldModal').modal('toggle');
                }
            }
        });
    }
}


function openSaveNewFieldModal() {
    hideAllFieldEditModalInputWarnings();
    clearAllFieldEditModalInputs();
    $('#editFieldModalHeader').text('Dadawanie nowej działki');
    $('#updateFieldBtn').unbind();
    $('#updateFieldBtn').click(function () {
        saveField();
    });
    $('#editFieldModal').modal();
}

function saveField() {
    if (checkRequiredFieldInEditFieldModal()) {
        $.ajax({
            url: "../../service/field/rest/field.php/",
            type: "PUT",
            dataType: 'json',
            contentType: "application/json",
            data: JSON.stringify(fillJSONEmptyField()),
            statusCode: {
                200: function (homeData) {
                    loadHomeData();
                    clearAllFieldEditModalInputs();
                    $('#editFieldModal').modal('toggle');
                }
            }
        });
    }
}


function fillEditableFields(field) {
    $('#fieldNumberEditInput').val(field.fieldNumber);
    $('#fieldDescriptionEditInput').val(field.description);
    $('#fieldPlantEditInput').val(field.plant);
    $('#fieldVarietesEditInput').val(field.varietes);
    $('#fieldSizeEditInput').val(field.ha);
    $('#fieldPlantPriceEditInput').val(field.plantPrice);
    $('#fieldTonsProHaEditInput').val(field.tonsProHa);
    $('#fieldOtherCostsEditInput').val(field.otherCosts);
}

function fillJSONEditableFields(field) {
    field.fieldNumber = $('#fieldNumberEditInput').val();
    field.description = $('#fieldDescriptionEditInput').val();
    field.plant = $('#fieldPlantEditInput').val();
    field.varietes = $('#fieldVarietesEditInput').val();
    field.ha = $('#fieldSizeEditInput').val();
    field.plantPrice = $('#fieldPlantPriceEditInput').val();
    field.tonsProHa = $('#fieldTonsProHaEditInput').val();
    field.otherCosts = $('#fieldOtherCostsEditInput').val();
    return field;
}


function fillJSONEmptyField() {
    var field = new Object();
    field.fieldNumber = $('#fieldNumberEditInput').val();
    field.description = $('#fieldDescriptionEditInput').val();
    field.plant = $('#fieldPlantEditInput').val();
    field.varietes = $('#fieldVarietesEditInput').val();
    field.ha = $('#fieldSizeEditInput').val();
    field.plantPrice = $('#fieldPlantPriceEditInput').val();
    field.tonsProHa = $('#fieldTonsProHaEditInput').val();
    field.otherCosts = $('#fieldOtherCostsEditInput').val();
    return field;
}

function checkRequiredFieldInEditFieldModal() {
    hideAllFieldEditModalInputWarnings();
    if ($('#fieldNumberEditInput').val().length === 0) {
        $('#fieldNumberEditInputWarning').text('*wymagane');
        return false;
    }
    if ($('#fieldDescriptionEditInput').val().length === 0) {
        $('#fieldDescriptionEditInputWarning').text('*wymagane');
        return false;
    }
    if ($('#fieldPlantEditInput').val().length === 0) {
        $('#fieldPlantEditInputInputWarning').text('*wymagane');
        return false;
    }
    if ($('#fieldVarietesEditInput').val().length === 0) {
        $('#fieldVarietesEditInputWarning').text('*wymagane');
        return false;
    }
    if ($('#fieldSizeEditInput').val().length === 0) {
        $('#fieldSizeEditInputWarning').text('*wymagane');
        return false;
    }
    if (!$.isNumeric($('#fieldSizeEditInput').val())) {
        $('#fieldSizeEditInputWarning').text('*wprowadz liczbę');
        return false;
    }
    if (!$.isNumeric($('#fieldPlantPriceEditInput').val())) {
        $('#fieldPlantPriceEditWarning').text('*wprowadz liczbę');
        return false;
    }
    if (!$.isNumeric($('#fieldTonsProHaEditInput').val())) {
        $('#fieldTonsProHaEditInputWarning').text('*wprowadz liczbę');
        return false;
    }
    if (!$.isNumeric($('#fieldOtherCostsEditInput').val())) {
        $('#fieldOtherCostsEditInputWarning').text('*wprowadz liczbę');
        return false;
    }
    return true;
}

function clearAllFieldEditModalInputs() {
    $('#fieldNumberEditInput').text('');
    $('#fieldDescriptionEditInput').text('');
    $('#fieldPlantEditInput').text('');
    $('#fieldVarietesEditInput').text('');
    $('#fieldSizeEditInput').text('');
    $('#fieldPlantPriceEditInput').text('');
    $('#fieldTonsProHaEditInput').text('');
    $('#fieldOtherCostsEditInput').text('');
}


function hideAllFieldEditModalInputWarnings() {
    $('#fieldNumberEditInputWarning').text('');
    $('#fieldDescriptionEditInputWarning').text('');
    $('#fieldPlantEditInputInputWarning').text('');
    $('#fieldVarietesEditInputWarning').text('');
    $('#fieldSizeEditInputWarning').text('');
    $('#fieldPlantPriceEditWarning').text('');
    $('#fieldTonsProHaEditInputWarning').text('');
    $('#fieldOtherCostsEditInputWarning').text('');
}