$(function () {
    $('#registration').click(function () {
        var login = $('#registrationLogin').val();
        var password = $('#registrationPassword').val();
        var password2 = $('#registrationPassword2').val();
        var mail = $('#registrationMail').val();
        var isInputOK = true;
        hiddAllElements();
        if (login.length == 0) {
            $('#requiredRegistrationLoginLable').show();
            isInputOK = false;
        }
        if (password.length == 0) {
            $('#requiredRegistrationPasswordLable').show();
            isInputOK = false;
        }
        if (password2.length == 0) {
            $('#requiredRegistrationPasswordLable2').show();
            isInputOK = false;
        }
        if (mail.length == 0) {
            $('#requiredRegistrationMailRegistratioLable').show();
            isInputOK = false;
        }
        if (password != password2) {
            $('#notEqualsRegistrationPasswordLable').show();
            isInputOK = false;
        }
        if (password.length < 8) {
            $('#lengthPasswordLable').show();
            isInputOK = false;
        }
        if (!isEmail(mail)) {
            $('#badRegistrationMailRegistratioLable').show();
            isInputOK = false;
        }
        if (isInputOK) {
            var formData = $('form').serialize();
            $.ajax({
                url: "../../service/rest/user/registration.php",
                data: 'login=' + login + '&password=' + CryptoJS.MD5(password) + '&mail=' + mail,
                type: "POST",
                contentType: "application/x-www-form-urlencoded",
                success: function (data, textStatus, response) {
                    //window.location.href = "/nr/";
                },
                error: function (data, textStatus, response) {
                    if (data.statusText === 'LoginError') {
                        $('#registrationLoginError').show();
                    }
                    else if (data.statusText === 'MailError') {
                        $('#registrationMailError').show();
                    }
                }
            });
        }
    });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function hiddAllElements() {
        $('#requiredRegistrationMailRegistratioLable').hide();
        $('#registrationMailError').hide();
        $('#badRegistrationMailRegistratioLable').hide();
        $('#requiredRegistrationPasswordLable').hide();
        $('#notEqualsRegistrationPasswordLable').hide();
        $('#lengthPasswordLable').hide();
        $('#requiredRegistrationLoginLable').hide();
        $('#registrationLoginError').hide();
    }

});
