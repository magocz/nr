function logout() {
    $.ajax({
        url: "../../service/rest/user/logout.php",
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        success: function () {
            window.location.href = "/nr/client/public/authorization/";
        },
        error: function () {
            alert('Bład podczas wylogowywania...')
        }
    });
}