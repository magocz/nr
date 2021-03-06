$(function () {


    $('#login').click(function () {
        var login = $('#loginLogin').val();
        var password = $('#loginPassword').val();
        var isInputOK = true;
        if (login.length == 0) {
            $('#requiredLoginLoginLable').show();
            isInputOK = false;
        }
        if (password.length == 0) {
            $('#requiredLoginPasswordLable').show();
            isInputOK = false;
        }
        if (isInputOK) {
            var formData = $('form').serialize();
            $.ajax({
                url: "../../../service/rest/user/login.php",
                data: 'login=' + login + '&password=' + CryptoJS.MD5(password),
                type: "POST",
                contentType: "application/x-www-form-urlencoded",
                success: function (data, textStatus, response) {
                    $('#loginError').hide();
                    window.location.href = "/nr/";
                },
                error: function (data, textStatus, response) {
                    $('#loginError').show();
                }
            });
        }
    });

    $("#loginLogin").blur(function () {
        if ($("#loginLogin").val().length != 0) {
            $('#requiredLoginLoginLable').hide();
        }
    });

    $("#loginPassword").blur(function () {
        if ($("#loginPassword").val().length != 0) {
            $('#requiredLoginPasswordLable').hide();
        }
    });

});


function login(){
    window.location.href = "/nr/login/";
}

