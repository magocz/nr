$(function () {
    checkIfLogedIn();

    $("#addSeasonModalContener").load("app/client/src/season/html/add-season-modal.html");
    $("#editFieldModalContener").load("app/client/src/home/html/edit-fiedl-modal.html");
    $("#deleteFieldModalContener").load("app/client/src/home/html/delete-field-modal.html");
    $("#addOperationModalContener").load("app/client/src/home/html/add-operation-modal.html");

    generateHomeContent();

});

function generateHomeContent() {
    $('#activeSeasonChartCombobox').change(function () {
        if ($('#activeSeasonChartCombobox').val() === 'plantsToVarietes') {
            createChart('app/service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToDescription') {
            createChart('app/service/rest/season/chart/plant/field-description.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
        if ($('#activeSeasonChartCombobox').val() === 'plantsToField') {
            createChart('app/service/rest/season/chart/plant/field-number.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
        }
    });

    $('#activeSeasonColumnChartCombobox').change(function () {
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToCostProField') {
            createChart('app/service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToCostProHa') {
            createChart('app/service/rest/season/chart/cost/pro-ha.php/', 'Koszt na hektar: ', 'Średni koszt na ha: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProfitProField') {
            createChart('app/service/rest/season/chart/profit/pro-field.php/', 'Zysk:', 'Całkowity zysk: ', 'Zysk w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToProfitProHa') {
            createChart('app/service/rest/season/chart/profit/pro-ha.php/', 'Zysk na hektar: ', 'Średni zysk na hektar:  ', 'Zysk w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToRevenuesProField') {
            createChart('app/service/rest/season/chart/revenues/pro-field.php/', 'Przychody ', 'Całkowity przychód: ', 'Przychody w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
        if ($('#activeSeasonColumnChartCombobox').val() === 'plantsToRevenuesProHa') {
            createChart('app/service/rest/season/chart/revenues/pro-ha.php/', 'Przychody na hektar: ', 'Średni przychód na hektar: ', 'Przychody w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
        }
    });
    if (!checkIfHasActiveSeason()) {
        return;
    }
    loadHomeData();
    searchFieldsTable();
}

function loadHomeData() {
    createChart('app/service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
    // loading first column chart
    createChart('app/service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');

    $("#homeTableCell").load("app/client/src/home/html/home-table.html");
    // loading first pie chart
    // loading the field table
    generateFieldsTable('app/service/rest/season/table/overwiew.php/');
}


function loadPipeChart() {
    createChart('app/service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
    // loading first column chart
    createChart('app/service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');

    $("#homeTableCell").load("app/client/src/home/html/home-table.html");
    // loading first pie chart
    // loading the field table
    generateFieldsTable('app/service/rest/season/table/overwiew.php/');
}


function loadPipeChart() {
    createChart('app/service/rest/season/chart/plant/variates.php/', 'Uprawiane rośliny: ', 'Całkowita powierzchnia: ', 'Powierzchnia w ha', 'HA', 'pie', 'activeSeasonPipeChartContener');
    // loading first column chart
}

function loadColumnChart() {
    createChart('app/service/rest/season/chart/cost/pro-field.php/', 'Koszt: ', 'Całkowity koszt: ', 'Koszt w zł', 'PLN', 'column', 'activeSeasonColumnChartContener');
}


function loadFieldsTable() {

    $("#homeTableCell").load("app/client/src/home/html/home-table.html");
    // loading first pie chart
    // loading the field table
    generateFieldsTable('app/service/rest/season/table/overwiew.php/');
}


function checkIfLogedIn() {
    $.ajax({
        url: 'app/service/rest/user/user.php/',
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (user) {
                if (user) {
                    $('#mainContent').show();
                } else {
                    window.location.href = "/login";
                }
            },
            403: function () {
                window.location.href = "/login";
            }
        }
    });
}


function checkIfHasActiveSeason() {
    $.ajax({
        url: 'app/service/rest/season/season.php/check',
        type: "GET",
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (season) {
                if (!season) {
                    $('.seasonData').hide();
                    $('#addFirstSeasonRow').show();
                    return false;
                }
                return true;
            },
            403: function () {
                window.location.href = "/login";
            }
        }
    });
    return true;
}
