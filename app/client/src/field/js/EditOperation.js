function openEditOperationModalDialog(operation) {
    fillOperationFormValues(operation);
    $('#editOperationdModalHeader').text('Dodaj zabieg do działki');
    $('#operationDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });
    configModalToDispalyCallender();
    $('#editOperationBtn').unbind();
    $('#editOperationBtn').click(function () {
        editOperation(operation);
    });

    $('#editOperationdModal').modal();
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
    if (checkRequiredFieldInEditOperationModal()) {
        $.ajax({
            url: "../app/service/rest/operation/operation.php/" + operation.id,
            type: "POST",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(createJSONObjFormFieldValues_OperationUpdate(getFieldIdFromUrl(), operation)),
            statusCode: {
                200: function () {
                    reloadField();
                    clearFieldInAddOperationModal();
                    $('#editOperationdModal').modal('toggle');
                }
            }
        });
    }
}


function checkRequiredFieldInEditOperationModal() {
    var isOk = true;
    if ($('#operationMeansNameEditInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationMeansDoseEditInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationCauseEditInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationEcoHarmEditInputs').val().length === 0) {
        isOk = false;
    }
    if ($('#operationCostProHaEditInputs').val().length === 0) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationMeansDoseEditInputs').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationCostProHaEditInputs').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#operationEcoHarmEditInputs').val())) {
        isOk = false;
    }
    isOk ? $('#editOperationBtn').attr("disabled", false) : $('#editOperationBtn').attr("disabled", true);
    return isOk;
}

function clearFieldInAddOperationModal() {
    $('#operationMeansNameEditInputs').val('');
    $('#operationCommentInputs').val('');
    $('#operationCauseEditInputs').val('0.0');
    $('#operationEcoHarmEditInputs').val('0.0');
    $('#operationCostProHaEditInputs').val('0.0');
    $('#operationCauseEditInputs').val('Nie określono');
}

function createJSONObjFormFieldValues_OperationUpdate(fieldId, operation) {
    operation.fieldId = fieldId;
    operation.date = $('#operationEditDate').val();
    operation.meansName = $('#operationMeansNameEditInputs').val();
    operation.meansType = $('#operationEditType').val();
    if ($('#operationMeansDoseEditOption').val() === 'lProHa') {
        operation.meansDoseInLProHa = $('#operationMeansDoseEditInputs').val();
        operation.meansDoseInKgProHa = null;
    } else if ($('#operationMeansDoseEditOption').val() === 'kgProHa') {
        operation.meansDoseInLProHa = null;
        operation.meansDoseInKgProHa = $('#operationMeansDoseEditInputs').val();
    }
    operation.cause = $('#operationCauseEditInputs').val();
    operation.economicHarm = $('#operationEcoHarmEditInputs').val();
    operation.costProHa = $('#operationCostProHaEditInputs').val();
    operation.comment = $('#operationCommentEditInputs').val();
    return operation;
}

function fillOperationFormValues(operation) {
    $('#operationEditDate').val(operation.date);
    $('#operationMeansNameEditInputs').val(operation.meansName);
    $('#operationEditType').val(operation.meansType);
    if (operation.meansDoseInLProHa == 0) {
        $('#operationMeansDoseEditInputs').val(operation.meansDoseInKgProHa);
        $('#operationMeansDoseEditOption').val('kgProHa');
    } else if (operation.meansDoseInKgProHa == 0) {
        $('#operationMeansDoseEditInputs').val(operation.meansDoseInLProHa);
        $('#operationMeansDoseEditOption').val('lProHa');
    }
    $('#operationCauseEditInputs').val(operation.cause);
    $('#operationEcoHarmEditInputs').val(operation.economicHarm);
    $('#operationCostProHaEditInputs').val(operation.costProHa);
    $('#operationCommentEditInputs').val(operation.comment);
}
