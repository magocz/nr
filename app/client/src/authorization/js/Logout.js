function logout() {
    $.ajax({
        url: "../app/service/rest/user/logout.php/",
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        success: function () {
            window.location.href = "/login/";
        },
        error: function () {
            $.ajax({
                url: "app/service/rest/user/logout.php/",
                type: "GET",
                contentType: "application/x-www-form-urlencoded",
                success: function () {
                    window.location.href = "/login/";
                },
                error: function () {
                    alert('Bład podczas wylogowywania...')
                }
            });
        }
    });
}