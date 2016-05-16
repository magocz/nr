function openEditOperationModalDialog(operation) {
    fillOperationFormValues(operation);
    $('#addOperationModalHeader').text('Dodaj zabieg do działki');
    $('#operationDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });
    $('#operationDate').datepicker('setDate', new Date());
    configModalToDispalyCallender();
    $('#addOperationBtn').unbind();
    $('#addOperationBtn').click(function () {
        editOperation(operation);
    });

    $('#addOperationdModal').modal();
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

function editOperation(operation) {
    if (checkRequiredFieldInAddOperationModal()) {
        $.ajax({
            url: "../../../service/rest/operation/operation.php/" + operation.id,
            type: "POST",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(createJSONObjFormFieldValues_OperationUpdate(getFieldIdFromUrl(), operation)),
            statusCode: {
                200: function () {
                    reloadField();
                    clearFieldInAddOperationModal();
                    $('#addOperationdModal').modal('toggle');
                }
            }
        });
    }
}


function checkRequiredFieldInAddOperationModal() {
    var isOk = true;
    if ($('#operationMeansNameInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationMeansDoseInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationCauseInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationEcoHarmInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationCostProHaInputs').val().length === 0) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationMeansDoseInputs').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationCostProHaInputs').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationEcoHarmInputs').val())) {
        isOk = false;
    }
    isOk ? $('#addOperationBtn').attr("disabled", false) : $('#addOperationBtn').attr("disabled", true);
    return isOk;
}

function clearFieldInAddOperationModal() {
    $('#operationMeansNameInputs').val('');
    $('#operationCommentInputs').val('');
    $('#operationCauseInputs').val('0.0');
    $('#operationEcoHarmInputs').val('0.0');
    $('#operationCostProHaInputs').val('0.0');
    $('#operationCauseInputs').val('Nie określono');
}

function createJSONObjFormFieldValues_OperationUpdate(fieldId, operation) {
    operation.fieldId = fieldId;
    operation.date = $('#operationDate').val();
    operation.meansName = $('#operationMeansNameInputs').val();
    operation.meansType = $('#operationType').val();
    if ($('#operationMeansDoseOption').val() === 'lProHa') {
        operation.meansDoseInLProHa = $('#operationMeansDoseInputs').val();
        operation.meansDoseInKgProHa = null;
    } else if ($('#operationMeansDoseOption').val() === 'kgProHa') {
        operation.meansDoseInLProHa = null;
        operation.meansDoseInKgProHa = $('#operationMeansDoseInputs').val();
    }
    operation.cause = $('#operationCauseInputs').val();
    operation.economicHarm = $('#operationEcoHarmInputs').val();
    operation.costProHa = $('#operationCostProHaInputs').val();
    operation.comment = $('#operationCommentInputs').val();
    return operation;
}

function fillOperationFormValues(operation) {
    $('#operationDate').val(operation.date);
    $('#operationMeansNameInputs').val(operation.meansName);
    $('#operationType').val(operation.meansType);
    if (operation.meansDoseInLProHa == 0) {
        $('#operationMeansDoseInputs').val(operation.meansDoseInKgProHa);
        $('#operationMeansDoseOption').val('kgProHa');
    } else if (operation.meansDoseInKgProHa == 0) {
        $('#operationMeansDoseInputs').val(operation.meansDoseInLProHa);
        $('#operationMeansDoseOption').val('lProHa');
    }
    $('#operationCauseInputs').val(operation.cause);
    $('#operationEcoHarmInputs').val(operation.economicHarm);
    $('#operationCostProHaInputs').val(operation.costProHa);
    $('#operationCommentInputs').val(operation.comment);
}
