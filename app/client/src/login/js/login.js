function login() {
    var login = $('#loginLogin').val();
    var password = $('#loginPassword').val();
    var isInputOK = true;
    if (login.length == 0) {
        isInputOK = false;
        $('#loginLogin').css("border", "2px solid red");
    }
    if (password.length == 0) {
        isInputOK = false;
        $('#loginPassword').css("border", "2px solid red");
    }
    if (isInputOK) {
        var formData = $('form').serialize();
        $.ajax({
            url: "../../../service/rest/user/login.php",
            data: 'login=' + login + '&password=' + CryptoJS.MD5(password),
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            success: function (data, textStatus, response) {
                window.location.href = "/nr/client/public";
            },
            error: function (data, textStatus, response) {
                $('#loginPassword').css("border", "2px solid red");
                $('#loginLogin').css("border", "2px solid red");
            }
        });
    }
};

