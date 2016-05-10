$(function () {
    var fieldId = getFieldIdFromUrl();
    if(fieldId){
        $.ajax({
            url: "../../service/field/rest/field.php/",
            type: "GET",
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            statusCode: {
                200: function (homeData) {
                    drawTable(homeData.data);
                }
            }
        });
    }
});

function getFieldIdFromUrl() {
    var url = window.location.href;
    var url_array = url.split("?");
    if (url_array.length == 2) {
        var url_array = url_array[1].split("=");
        if (url_array.length == 2) {
            return url_array[1];
        }
    }
}