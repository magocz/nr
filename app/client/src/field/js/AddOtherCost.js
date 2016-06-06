function openAddOtherCostModalDialog() {
    $('#addOtherCostnModalHeader').text('Dodaj koszt do działki');
    $('#otherCostDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });
    $('#otherCostDate').datepicker('setDate', new Date());
    configModalToDispalyCallender();
    $('#addOtherCostnBtn').unbind();
    $('#addOtherCostnBtn').click(function () {
        addOtherCost();
    });

    $('#addOtherCostModal').modal();
}

var fillOtherCostModalDialogFields = function (data) {
    $('#otherCostDate').val(data.date);
    $('#otherCostComment').val(data.comment);
    $('#otherCost').val(data.cost);
};

function openEditOtherCostModalDialog(data) {
    fillOtherCostModalDialogFields(data);
    $('#addOtherCostnModalHeader').text('Edytuj koszt z dnia ' + data.date);
    $('#otherCostDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });
    configModalToDispalyCallender();
    $('#addOtherCostnBtn').unbind();
    $('#addOtherCostnBtn').click(function () {
        editOtherCost(data);
    });

    $('#addOtherCostModal').modal();
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

function addOtherCost() {
    if (checkRequiredOtherCostFieldsModal()) {
        $.ajax({
            url: "../app/service/rest/other-cost/other-cost.php/",
            type: "PUT",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(createJSONObjFormFieldValues_OtherCost(getFieldIdFromUrl())),
            statusCode: {
                200: function () {
                    reloadField();
                    clearAddOtherCostFieldsModal();
                    $('#addOtherCostModal').modal('toggle');
                }
            }
        });
    }
}


function editOtherCost(date) {
    if (checkRequiredOtherCostFieldsModal()) {
        var newOtherCostDate = JSON.stringify(createJSONObjFormFieldValues_OtherCost(getFieldIdFromUrl()));
        newOtherCostDate.otherCostId = 2;
        $.ajax({
            url: "../app/service/rest/other-cost/other-cost.php/" + date.id,
            type: "POST",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: newOtherCostDate,
            statusCode: {
                200: function () {
                    reloadField();
                    clearAddOtherCostFieldsModal();
                    $('#addOtherCostModal').modal('toggle');
                }
            }
        });
    }
}


function checkRequiredOtherCostFieldsModal() {
    var isOk = true;
    if ($('#otherCostComment').val().length === 0) {
        isOk = false;
    }
    if ($('#otherCost').val().length === 0) {
        isOk = false;
    }
    if (!$.isNumeric($('#otherCost').val())) {
        isOk = false;
    }
    isOk ? $('#addOtherCostnBtn').attr("disabled", false) : $('#addOtherCostnBtn').attr("disabled", true);
    return isOk;
}

function clearAddOtherCostFieldsModal() {
    $('#otherCostComment').val('');
}

function createJSONObjFormFieldValues_OtherCost(fieldId) {
    var otherCost = {};
    otherCost.fieldId = fieldId;
    otherCost.date = $('#otherCostDate').val();
    otherCost.comment = $('#otherCostComment').val();
    otherCost.cost = $('#otherCost').val();
    return otherCost;
}

