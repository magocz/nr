function createChart(restUrl, newTitle, newSubtitle, yAxisDesc, unit, chartType, renderObject) {
    var chart = getEmptyChart(newTitle, newSubtitle, yAxisDesc, unit, chartType, renderObject);
    chart.showLoading("Generowanie danych...");
    $.ajax({
        url: restUrl,
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (homeData) {
                chart.hideLoading();
                if (unit === 'PLN') {
                    generateChart_Unit_PLN(homeData, newTitle, newSubtitle, yAxisDesc, chartType, renderObject);
                    return;
                }
                if (unit === 'HA') {
                    generateChart_Unit_HA(homeData, newTitle, newSubtitle, yAxisDesc, chartType, renderObject);
                }
            },
            403: function () {
                window.location.href = "/nr/login";
            }
        }
    });
}

function getEmptyChart(newTitle, newSubtitle, yAxisDesc, unit, chartType, renderObject) {
    return new Highcharts.Chart(
        {
            chart: {
                type: chartType,
                renderTo: renderObject
            },
            title: {
                text: newTitle
            },
            subtitle: {
                text: newSubtitle
            },

            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                    }
                }
            },
            yAxis: {
                title: {
                    text: yAxisDesc
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
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b>  <br/>'
            }
        }
    );
}


function generateChart_Unit_HA(data, newTitle, newSubtitle, yAxisDesc, chartType, renderObject) {
    $('#' + renderObject).highcharts({
        chart: {
            type: chartType
        },
        title: {
            text: newTitle + data.data.seasonName
        },
        subtitle: {
            text: newSubtitle + formatPrice(data.data.costs) + ' ha'
        },

        unit: 'ds',

        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                }
            }
        },
        yAxis: {
            title: {
                text: yAxisDesc
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
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ha<br/>'
        },

        series: [{
            name: data.data.seasonName,
            colorByPoint: true,
            data: data.data.seriesData
        }],
        drilldown: {
            drillUpButton: {
                relativeTo: 'spacingBox',
                position: {
                    y: 0,
                    x: 0
                }
            },
            series: data.data.drilldownData
        }
    });
}


function generateChart_Unit_PLN(data, newTitle, newSubtitle, yAxisDesc, chartType, renderObject) {
    $('#' + renderObject).highcharts({
        chart: {
            type: chartType
        },
        title: {
            text: newTitle + data.data.seasonName
        },
        subtitle: {
            text: newSubtitle + formatPrice(data.data.costs) + ' zł'
        },

        unit: 'ds',

        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '12px'
                }
            }
        },
        yAxis: {
            title: {
                text: yAxisDesc
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
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> zł<br/>'
        },

        series: [{
            name: data.data.seasonName,
            colorByPoint: true,
            data: data.data.seriesData
        }],
        drilldown: {
            drillUpButton: {
                relativeTo: 'spacingBox',
                position: {
                    y: 0,
                    x: 0
                }
            },
            series: data.data.drilldownData
        }
    });
}

function formatPrice(price) {
    price = parseFloat(price).toFixed(2);
    return price.toString().reverse().replace(/((?:\d{2})\d)/g, '$1 ').reverse();
}

// Need to extend String prototype for convinience
String.prototype.reverse = function () {
    return this.split('').reverse().join('');
}