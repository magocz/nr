$(function () {

    $("#activeSeasonChart").load("../src/home/html/plants-to-varietes-pipe-char.html"); // load first Pipe chart
    $("#activeSeasonCost").load("../src/home/html/cost-to-plants-column-chart.html"); // load first Column chart
    $("#homeTableCell").load("../src/home/html/home-table.html"); // load home-content table

    $.ajax({
        url: "../../service/home/rest/season-fields.php/",
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                drawTable(homeData.data);
            }
        }
    });

    function drawTable(data) {
        for (var i = 0; i < data.length; i++) {
            drawRow(data[i]);
        }
    }

    function drawRow(rowData) {
        var row = $("<tr />")
        $("#homeTableActiveSeasonData").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
        row.append($("<td> " + rowData.fieldNumber + "</td>"));
        row.append($("<td>" + rowData.description + "</td>"));
        row.append($("<td>" + rowData.plant + "</td>"));
        row.append($("<td>" + rowData.varietes + "</td>"));
        row.append($("<td>" + rowData.ha + "</td>"));
        row.append($("<td>" + rowData.operationsNumber + "</td>"));
        row.append($("<td>  <i onclick='getFieldDetails("+ rowData.id +")' class='fa fa-arrow-circle-o-right' style='font-size:28px; cursor:pointer;'/></td>"));
    }


    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            $("#activeSeasonChart").load("../src/home/html/plants-to-varietes-pipe-char.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            $("#activeSeasonChart").load("../src/home/html/plants-to-varietes-pipe-char.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            $("#activeSeasonChart").load("../src/home/html/plants-to-varietes-pipe-char.html");
        }
    });


});

function getFieldDetails(fieldId) {
   window.location.href = '/nr/client/public/field/?fieldId=' + fieldId;
}