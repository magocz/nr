$(function () {

    $.ajax({
        url: "../../../service/rest/user/user.php/",
        type: "GET",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        statusCode: {
            200: function (data) {
                loadDataToUserImpust(data);
                $('#userDataLoadIcon').hide();
                $('#userDataTable').show();
            },
            500: function () {
                alert('Bład serwera podczas zapytania...');
            }
        }
    });

    function loadDataToUserImpust(data) {
        $('#loginInput').val(data.LOGIN);
        $('#firstNameInput').val(data.FIRST_NAME);
        $('#lastNameInput').val(data.LAST_NAME);
        $('#mailInput').val(data.MAIL);
    }

    function getDataFromUserImpust() {
        return {
            login: $('#loginInput').val(),
            firstName: $('#firstNameInput').val(),
            lastName: $('#lastNameInput').val(),
            mail: $('#mailInput').val()
        };
    }
});