$(function () {

    $("#activeSeasonChart").load("client/html/home/plantsToVarietesChar.html"); // load first Pipe chart
    $("#activeSeasonCost").load("client/html/home/costToPlantsChar.html"); // load first Column chart
    $("#homeTableCell").load("client/html/home/home-table.html"); // load home table

    $.ajax({
        url: "service/rest/season.php/home-table/",
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
        row.append($("<td>" + rowData.fieldNumber + "</td>"));
        row.append($("<td>" + rowData.description + "</td>"));
        row.append($("<td>" + rowData.plant + "</td>"));
        row.append($("<td>" + rowData.varietes + "</td>"));
        row.append($("<td>" + rowData.ha + "</td>"));
        row.append($("<td>" + rowData.operationsNumber + "</td>"));
        row.append($("<td onclick='myfunction()'><i class='fa fa-arrow-circle-o-right' style='font-size:28px; cursor:pointer;'><a name='dupa'></a></i></td>"));

    }


    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            $("#activeSeasonChart").load("client/html/home/plantsToVarietesChar.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            $("#activeSeasonChart").load("client/html/home/plantsToFieldDescriptionChart.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            $("#activeSeasonChart").load("client/html/home/plantsToFieldChart.html");
        }
    });


});

function myfunction() {
    console.log('dsds');
}