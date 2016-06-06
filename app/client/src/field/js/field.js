$(function () {
    $("#editOperationModalContener").load("../app/client/src/field/html/edit-operation-modal.html");
    $("#deleteOperationModalContener").load("../app/client/src/field/html/delete-field-modal.html");
    $("#addOperationModalContener").load("../app/client/src/field/html/add-operation-modal.html");
    $("#deleteFieldModalContener").load("../app/client/src/field/html/delete-field-modal.html");
    $("#editFieldModalContener").load("../app/client/src/field/html/edit-fiedl-modal.html");
    $("#deleteOtherCostModalContener").load("../app/client/src/field/html/delete-other-cost-modal.html");
    $("#addOtherCostModalContener").load("../app/client/src/field/html/add-other-cost-modal.html");

    reloadField();
});

function reloadField() {

    $("#operationsCell").load("../app/client/src/field/html/operations-table.html");
    $("#otherCostCell").load("../app/client/src/field/html/other-cost-table.html");

    var fieldId = getFieldIdFromUrl();

    $('#fieldHeader').append(
        "<i class='fa fa-refresh w3-spin' style='font-size:55px; cursor:pointer; margin-right: 3px;padding: 5px ;  display: table;margin: 0 auto' />"
    );

    if (fieldId) {
        loadOperations(fieldId);
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
        '<div><b>Powierzchnia pola: </b>' + data.ha + ' ha</div>' +
        '<div><b>Przeprowadzone operacje: </b>' + data.operationsNumber + '</div>' +
        '<div><b>Cena za tonę: </b>' + data.plantPrice + ' zł</div>' +
        '<div><b>Ton na hektar: </b>' + data.tonsProHa + '</div>';
    $('#fieldLeftInfoDivId').empty();
    $('#fieldLeftInfoDivId').append(leftContent);

    var rightContent =
        '<div><b>Całkowity koszt: </b>' + formatPrice(data.totalCost) + ' zł</div>' +
        '<div><b>Całkowity koszt na hektar: </b>' + formatPrice(data.totalCost / data.ha) + ' zł</div>' +
        '<div><b>Koszt nawozów: </b>' + formatPrice(data.totalFertilizerOperationsCost * data.ha) + ' zł</div>' +
        '<div><b>Koszt nawozów na hektar: </b>' + formatPrice(data.totalFertilizerOperationsCost) + ' zł</div>' +
        '<div><b>Koszt ochrony roślin: </b>' + formatPrice(data.totalPlantProtectionOperationsCost * data.ha) + ' zł</div>' +
        '<div><b>Koszt ochrony roślin na hektar: </b>' + formatPrice(data.totalPlantProtectionOperationsCost) + ' zł</div>' +
        '<div><b>Przychody: </b>' + formatPrice(data.plantPrice * data.tonsProHa) + ' zł</div>';
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
