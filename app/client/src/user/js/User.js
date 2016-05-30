$(function () {
    checkIfLogedIn();
    $.ajax({
        url: '../app/service/rest/user/user.php/',
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
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

function checkIfLogedIn() {
    $.ajax({
        url: '../app/service/rest/user/user.php/',
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (user) {
                if (user) {
                    $('#mainContent').show();
                } else {
                    window.location.href = "/login";
                }
            },
            403: function () {
                window.location.href = "/login";
            }
        }
    });
}