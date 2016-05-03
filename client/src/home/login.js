$(function () {
    $('#registrationyPanelLableId').click(function () {
        $('#registrationyContener').slideDown(500);
        $('#loginContaner').slideUp(500);
        $('#forgotPasswordContener').slideUp(500);
    });

    $('#loginPanelLableId').click(function () {
        $('#registrationyContener').slideUp(500);
        $('#loginContaner').slideDown(500);
        $('#forgotPasswordContener').slideUp(500);
    });

    $('#forgotPassword').click(function () {
        $('#registrationyContener').slideUp(500);
        $('#loginContaner').slideUp(500);
        $('#forgotPasswordContener').slideDown(500);
    });

    forgotPassword

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
                url: "service/rest/login.php",
                data: 'login=' + login + '&password=' + CryptoJS.MD5(password),
                type: "POST",
                dataType: "x-www-form-urlencoded",
                success: function (data, textStatus, jqXHR) {
                    console.log(textStatus);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        }
    });

    $("#loginLogin").blur(function () {
        if ($("#loginLogin").val().length == 0) {
            $('#requiredLoginLoginLable').show();
        }
        else {
            $('#requiredLoginLoginLable').hide();
        }
    });

    $("#loginPassword").blur(function () {
        if ($("#loginPassword").val().length == 0) {
            $('#requiredLoginPasswordLable').show();
        }
        else {
            $('#requiredLoginPasswordLable').hide();
        }
    });

});

