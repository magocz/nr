function openFieldEditModalDialog(field) {
    checkRequiredFieldInEditFieldModal();
    $('#editFieldModalHeader').text('Edycja działki: ' + field.fieldNumber);
    fillEditableFields(field);
    $('#updateFieldBtn').unbind();
    $('#updateFieldBtn').click(function () {
        updateField(field);
    });
    $('#editFieldModal').modal({});
}


function fillEditableFields(field) {
    console.log(field);
    $('#fieldNumberEditInput').val(field.fieldNumber);
    $('#fieldDescriptionEditInput').val(field.description);
    $('#fieldPlantEditInput').val(field.plant);
    $('#fieldVarietesEditInput').val(field.varietes);
    $('#fieldSizeEditInput').val(field.ha);
    $('#fieldPlantPriceEditInput').val(field.plantPrice);
    $('#fieldTonsProHaEditInput').val(field.tonsProHa);

    $('#fieldSeedsKgProHaEditInput').val(field.seedsProHa);
    $('#fieldTaxEditInput').val(field.tax);
    $('#fieldSeedsCostEditInput').val(field.seedsCost);
    $('#fieldLeaseEditInput').val(field.leaseCost);
}

function updateField(field) {
    if (checkRequiredFieldInEditFieldModal()) {
        $.ajax({
            url: "app/service/rest/field/field.php/" + field.id,
            type: "POST",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(updateJSONObjFormFieldValues(field)),
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

function updateJSONObjFormFieldValues(field) {
    field.fieldNumber = $('#fieldNumberEditInput').val();
    field.description = $('#fieldDescriptionEditInput').val();
    field.plant = $('#fieldPlantEditInput').val();
    field.varietes = $('#fieldVarietesEditInput').val();
    field.ha = $('#fieldSizeEditInput').val();
    field.plantPrice = $('#fieldPlantPriceEditInput').val();
    field.tonsProHa = $('#fieldTonsProHaEditInput').val();
    field.seedsProHa = $('#fieldSeedsKgProHaEditInput').val();
    field.tax = $('#fieldTaxEditInput').val();
    field.seedsCost = $('#fieldSeedsCostEditInput').val();
    field.leaseCost = $('#fieldLeaseEditInput').val();
    return field;
}

function createJSONObjFormFieldValues() {
    var field = {};
    field.fieldNumber = $('#fieldNumberEditInput').val();
    field.description = $('#fieldDescriptionEditInput').val();
    field.plant = $('#fieldPlantEditInput').val();
    field.varietes = $('#fieldVarietesEditInput').val();
    field.ha = $('#fieldSizeEditInput').val();
    field.plantPrice = $('#fieldPlantPriceEditInput').val();
    field.tonsProHa = $('#fieldTonsProHaEditInput').val();

    field.seedsProHa = $('#fieldSeedsKgProHaEditInput').val();
    field.tax = $('#fieldTaxEditInput').val();
    field.seedsCost = $('#fieldSeedsCostEditInput').val();
    field.leaseCost = $('#fieldLeaseEditInput').val();
    return field;
}

function clearAllFieldEditModalInputs() {
    $('#fieldNumberEditInput').val('');
    $('#fieldDescriptionEditInput').val('');
    $('#fieldPlantEditInput').val('');
    $('#fieldVarietesEditInput').val('');
    $('#fieldSizeEditInput').val('0.0');
    $('#fieldTonsProHaEditInput').val('0.0');
    $('#fieldPlantPriceEditInput').val('0.0');
    $('#fieldSeedsKgProHaEditInput').val('0.0');
    $('#fieldTaxEditInput').val('0.0');
    $('#fieldSeedsCostEditInput').val('0.0');
    $('#fieldLeaseEditInput').val('0.0');
}

function checkRequiredFieldInEditFieldModal() {
    var isOk = true;
    if ($('#fieldNumberEditInput').val().length === 0) {
        isOk = false;
    }
    if ($('#fieldDescriptionEditInput').val().length === 0) {
        isOk = false;
    }
    if ($('#fieldPlantEditInput').val().length === 0) {
        isOk = false;
    }
    if ($('#fieldVarietesEditInput').val().length === 0) {
        isOk = false;
    }
    if ($('#fieldSizeEditInput').val().length === 0) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldSizeEditInput').val()) || parseFloat($('#fieldSizeEditInput').val()) === 0) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldTonsProHaEditInput').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldPlantPriceEditInput').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldSeedsKgProHaEditInput').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldTaxEditInput').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldSeedsCostEditInput').val())) {
        isOk = false;
    }
    if (!$.isNumeric($('#fieldLeaseEditInput').val())) {
        isOk = false;
    }
    isOk ? $('#updateFieldBtn').attr("disabled", false) : $('#updateFieldBtn').attr("disabled", true);
    return isOk;
}

