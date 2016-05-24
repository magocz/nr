function openSaveNewFieldModal() {
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
            url: "app/service/rest/field/field.php/",
            type: "PUT",
            dataType: 'json',
            contentType: "application/json",
            data: JSON.stringify(createJSONObjFormFieldValues()),
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
