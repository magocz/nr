$(function () {

    $("#editFieldModalContener").load("../src/home/html/edit-fiedl-modal.html");
    $("#deleteFieldModalContener").load("../src/home/html/delete-field-modal.html");
    $("#addOperationModalContener").load("../src/home/html/add-operation-modal.html");

    loadHomeData();


    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            createChart('../../service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            createChart('../../service/rest/season/chart/plant/field-description.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            createChart('../../service/rest/season/chart/plant/field-number.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
    });

    $('#activeSeasonColumnChartCombobox').change(function () {
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToCostProField') {
            createChart('../../service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToCostProHa') {
            createChart('../../service/rest/season/chart/cost/pro-ha.php/', 'Koszt na hektar: ', 'Średni koszt na ha: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProfitProField') {
            createChart('../../service/rest/season/chart/profit/pro-field.php/', 'Zysk:', 'Całkowity zysk: ', 'Zysk w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProfitProHa') {
            createChart('../../service/rest/season/chart/profit/pro-ha.php/', 'Zysk na hektar: ', 'Średni zysk na hektar:  ', 'Zysk w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToRevenuesProField') {
            createChart('../../service/rest/season/chart/revenues/pro-field.php/', 'Przychody ', 'Całkowity przychód: ', 'Przychody w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToRevenuesProHa') {
            createChart('../../service/rest/season/chart/revenues/pro-ha.php/', 'Przychody na hektar: ', 'Średni przychód na hektar: ', 'Przychody w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
    });

    searchFieldsTable();

});

function loadHomeData() {
    $("#homeTableCell").load("../src/home/html/home-table.html");
    // loading first pie chart
    createChart('../../service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
    // loading first column chart
    createChart('../../service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
    // loading the field table
    generateFieldsTable('../../service/rest/season/table/overwiew.php/');
}
