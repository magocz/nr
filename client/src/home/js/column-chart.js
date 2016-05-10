function getColumnChart(restUrl) {
    var renderObject = 'activeSeasonColumnChartContener';
    var chart = getEmptyColumnChart(renderObject);
    chart.showLoading("Generowanie danych...");
    $.ajax({
        url: restUrl,
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                chart.hideLoading();
                generateHomeColumnChart(homeData, renderObject);
            }
        }
    });
}

function getEmptyColumnChart(renderObject) {
    return new Highcharts.Chart(
        {
            chart: {
                type: 'column',
                renderTo: renderObject
            },
            title: {
                text: 'Koszt uprawianych roślin: '
            },
            subtitle: {
                text: 'Całkowity koszt uprawy: '
            },

            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Całkowity koszt w zł'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 1
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} zł</b>  <br/>'
            }
        }
    );
}


function generateHomeColumnChart(data, renderObject) {
    $('#' + renderObject).highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Koszt uprawianych roślin: ' + data.activeSeasonChart.seasonName
        },
        subtitle: {
            text: 'Całkowity koszt uprawy: ' + data.activeSeasonChart.cost + ' zł'
        },

        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Całkowity koszt w zł'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 1
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} zł</b>  <br/>'
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
