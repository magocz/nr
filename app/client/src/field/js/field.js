$(function () {


   // $("#addOperationModalContener").load("../../src/home/html/add-operation-modal.html");
    $("#editOperationModalContener").load("../app/client/src/field/html/edit-operation-modal.html");
    $("#deleteOperationModalContener").load("../app/client/src/home/html/delete-field-modal.html");

    reloadField();

});

function reloadField() {

    $("#operationsCell").load("../app/client/src/field/html/operations-table.html");
    var fieldId = getFieldIdFromUrl();

    $('#fieldHeader').append(
        "<i class='fa fa-refresh w3-spin' style='font-size:55px; cursor:pointer; margin-right: 3px;padding: 5px ;  display: table;margin: 0 auto' />"
    );

    if (fieldId) {
        $.ajax({
            url: "../app/service/rest/field/field.php/" + fieldId,
            type: "GET",
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            statusCode: {
                200: function (data) {
                    fillFieldContent(data);
                    drawFieldOperationsTable(data.fertilizerOperations);
                    drawFieldOperationsTable(data.plantProtectionOperations);
                    $("#fieldOperationsTable").tablesorter({
                            headers: {
                                5: {sorter: false},
                            }
                        }
                    );
                },
                403: function () {
                    window.location.href = "/login";
                }
            }
        });
    }
}

function fillFieldContent(data) {
    $('#fieldHeader').empty();
    $('#fieldHeader').append('Działka numer: ' + data.fieldNumber +
        "<i onclick='openDeleteFieldModalDialog(" + JSON.stringify(data) + ");' class='fa fa-trash-o' style='font-size:25px; cursor:pointer; float: right; padding: 5px' data-toggle='tooltip'  title='Usuń'/>" +
        "<i id ='generateFieldExceFileBtn' onclick='generateExcelFromField(" + data.id + ")' class='fa fa-download' style='font-size:25px; cursor:pointer; margin-right: 3px;float: right;padding: 5px ' data-toggle='tooltip'  title='Generuj excela'/>" +
        "<i onclick='openFieldEditModalDialog(" + JSON.stringify(data) + ")' class='fa fa-edit' style='font-size:25px; cursor:pointer; margin-right: 3px;float: right;padding: 5px ' data-toggle='tooltip'  title='Edytuj'/>"
    );

    var leftContent =
        '<div><b>Opis pola: </b>' + data.description + '</div>' +
        '<div><b>Uprawiana roślina: </b>' + data.plant + '</div>' +
        '<div><b>Odmiana: </b>' + data.varietes + '</div>' +
        '<div><b>Powierzchnia pola: </b>' + data.ha + '</div>' +
        '<div><b>Przeprowadzone operacje: </b>' + data.operationsNumber + '</div>' +
        '<div><b>Cena za tonę: </b>' + data.plantPrice + '</div>' +
        '<div><b>Ton na hektar: </b>' + data.tonsProHa + '</div>';
    $('#fieldLeftInfoDivId').empty();
    $('#fieldLeftInfoDivId').append(leftContent);

    var rightContent =
        '<div><b>Całkowity koszt: </b>' + formatPrice(data.totalCost * data.ha) + '</div>' +
        '<div><b>Całkowity koszt na hektar: </b>' + data.totalCost + '</div>' +
        '<div><b>Koszt nawozów: </b>' + formatPrice(data.totalFertilizerOperationsCost * data.ha) + '</div>' +
        '<div><b>Koszt nawozów na hektar: </b>' + formatPrice(data.totalFertilizerOperationsCost) + '</div>' +
        '<div><b>Koszt ochrony roślin: </b>' + formatPrice(data.totalPlantProtectionOperationsCost * data.ha) + '</div>' +
        '<div><b>Koszt ochrony roślin na hektar: </b>' + formatPrice(data.totalPlantProtectionOperationsCost) + '</div>' +
        '<div><b>Dodatkowe koszty: </b>' + formatPrice(data.totalOtherCosts) + '</div>';
    $('#fieldRightInfoDivId').empty();
    $('#fieldRightInfoDivId').append(rightContent);
}


function formatPrice(price) {
    price = parseFloat(price).toFixed(2);
    return price.toString().reverse().replace(/((?:\d{2})\d)/g, '$1 ').reverse();
}

// Need to extend String prototype for convinience
String.prototype.reverse = function () {
    return this.split('').reverse().join('');
}

function generateExcelFromField(fieldId) {

    $('#generateFieldExceFileBtn').removeClass('fa fa-download');
    $('#generateFieldExceFileBtn').addClass('fa fa-refresh w3-spin');

    $.ajax({
        url: '../app/service/rest/excel/field/excel-generator.php/' + fieldId,
        type: "GET",
        dataType: 'application/vnd.ms-excel',
        contentType: "application/vnd.ms-excel",
        statusCode: {
            200: function (data) {
                window.location = '../app/service/rest/excel/field/excel-generator.php/' + fieldId;
                $('#generateFieldExceFileBtn').removeClass('fa fa-refresh w3-spin');
                $('#generateFieldExceFileBtn').addClass('fa fa-download');
            }
        }
    });

}

function getFieldIdFromUrl() {
    var url = window.location.href;
    var url_array = url.split("#");
    if (url_array.length == 2) {
        return url_array[1];
    }
}
