$(function ()  {
    checkIfLogedIn();
    $("#addSeasonModalContener").load("../app/client/src/season/html/add-season-modal.html");
    loadSeasonData();
});

function loadSeasonData() {
    $("#seasonsCell").load("../app/client/src/season/html/seasons-table.html");
    generateSeasonsTable();
}

function checkIfLogedIn() {
    $.ajax({
        url: '../app/service/rest/user/user.php/',
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (user) {
                if(user){
                    $('#mainContent').show();
                }else{
                    window.location.href = "/login";
                }
            },
            403: function () {
                window.location.href = "/login";
            }
        }
    });
}
