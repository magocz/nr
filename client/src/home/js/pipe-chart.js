function getPipeChart(restUrl) {
    var renderObject = 'activeSeasonPipeChartContener';
    var chart = getEmptyPipeChart('Uprawiane rośliny: ', 'Całkowita powierzchnia upraw: ', renderObject);
    chart.showLoading("Generowanie danych...");
    $.ajax({
        url: restUrl,
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                chart.hideLoading();
                generateHomePipeChart(homeData, renderObject);
            }
        }
    });
}

function getEmptyPipeChart(chartTile, chartSubtile, renderObject) {
    return new Highcharts.Chart({
        chart: {
            type: 'pie',
            renderTo: renderObject
        },
        title: {
            text: chartTile
        },
        subtitle: {
            text: chartSubtile
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y} ha'
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} ha</b>  <br/>'
        }
    });
}


function generateHomePipeChart(data, renderObject) {
    $('#' + renderObject).highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Uprawiane rośliny: ' + data.activeSeasonChart.seasonName
        },
        subtitle: {
            text: 'Całkowita powierzchnia upraw: ' + data.activeSeasonChart.haCount + ' ha'
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y} ha'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} ha</b>  <br/>'
        },
        series: [{
            name: data.activeSeasonChart.seasonName,
            colorByPoint: true,
            data: data.activeSeasonChart.seriesData
        }],
        drilldown: {
            series: data.activeSeasonChart.drilldownData
        }
    });
}
