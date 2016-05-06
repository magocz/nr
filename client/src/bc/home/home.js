$(function () {

    $("#activeSeasonChart").load("client/html/active-season/plantsToVarietesChar.html"); // load first Pipe chart
    $("#activeSeasonCost").load("client/html/active-season/costToPlantsChar.html"); // load first Column chart
    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            $("#activeSeasonChart").load("client/html/active-season/plantsToVarietesChar.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            $("#activeSeasonChart").load("client/html/active-season/plantsToFieldDescriptionChart.html");
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            $("#activeSeasonChart").load("client/html/active-season/plantsToFieldChart.html");
        }
    });

});

