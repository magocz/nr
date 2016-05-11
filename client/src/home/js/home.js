$(function () {


    loadHomeData();


    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            getPipeChart('../../service/home/rest/plants-to-varietes.php/');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            getPipeChart('../../service/home/rest/plants-to-description.php/');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            getPipeChart('../../service/home/rest/plants-to-field.php/');
        }
    });

    $('#activeSeasonColumnChartCombobox').change(function () {
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToCost') {
            getColumnChart('../../service/home/rest/plants-to-cost.php/');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProceeds') {
            getColumnChart('../../service/home/rest/plants-to-proceeds.php/');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProfit') {
            getColumnChart('../../service/home/rest/plants-to-profit.php/');
        }
    });

    searchFieldsTable();
});

function loadHomeData() {
    $("#homeTableCell").load("../src/home/html/home-table.html");
    // loading first pipe chart
    getPipeChart('../../service/home/rest/plants-to-varietes.php/');
    // loading first column chart
    getColumnChart('../../service/home/rest/plants-to-cost.php/');
    // loading the field table
    generateFieldsTable('../../service/home/rest/season-fields.php/');
}
