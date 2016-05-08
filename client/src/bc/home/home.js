$(function () {

    $("#activeSeasonChart").load("client/html/home/plantsToVarietesChar.html"); // load first Pipe chart
    $("#activeSeasonCost").load("client/html/home/costToPlantsChar.html"); // load first Column chart
    $("#homeTableCell").load("client/html/home/home-table.html"); // load home table

    // load data to the table
    $('#table_id').DataTable();

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

