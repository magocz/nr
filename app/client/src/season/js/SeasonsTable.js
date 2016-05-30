function generateSeasonsTable() {
    $('#seasonTableLoadIcon').show();
    $.ajax({
        url: "../app/service/rest/season/season.php/",
        type: "GET",
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        statusCode: {
            200: function (data) {
                $('#seasonTableLoadIcon').hide();
                drawTable(data);
                $("#allSesonsTable").tablesorter({
                        sortList: [[7, 1]]
                    }
                );
            }
        }
    });
}


function drawTable(data) {
    $("#allSesonsTable").append('<tbody />');
    for (var i = 0; i < data.length; i++) {
        drawRow(data[i]);
    }
}


function drawRow(rowData) {
    var row = $("<tr />")
    $("#allSesonsTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
    row.append($("<td onclick='setAsActiveSeason(this)'> " + rowData.name + "</td>"));
    row.append($("<td>" + getSeasonDataOr_(rowData.seasonFieldsCount) + "</td>"));
    row.append($("<td>" + getSeasonDataOr_(rowData.seasonOperationsCount) + "</td>"));
    row.append($("<td>" + getSeasonIfno(rowData.totalSeasonCosts) + "</td>"));
    row.append($("<td>" + getSeasonIfno(rowData.totalSeasonCostsProHa) + "</td>"));
    row.append($("<td>" + getSeasonIfno(rowData.totalSeasonProfi) + "</td>"));
    row.append($("<td>" + getSeasonIfno(rowData.totalSeasonProfiProHa) + "</td>"));
    row.append($("<td><span hidden>" + rowData.active + "</span>" + (rowData.active === 1 ?
        "<i onclick='setAsActiveSeason({elem: this, sesonId: " + rowData.id + "})' class='fa fa-heart' style='font-size:15px; cursor:pointer; ' data-toggle='tooltip'  title='Ustaw jako aktywny'/>"
            :
        "<i onclick='setAsActiveSeason({elem: this, sesonId: " + rowData.id + "})' class='fa fa-heart-o' style='font-size:15px; cursor:pointer; data-toggle='tooltip'  title='Ustaw jako aktywny'/>" ) +

        "</td>"));
}

function seasonsTableHeaderClick($ascIcon, $descIcon, $sortIcon) {
    if ($($sortIcon).is(":visible")) {
        hideAllHeaderSort();
        $($ascIcon).show();
    } else if ($($ascIcon).is(":visible")) {
        hideAllHeaderSort();
        $($descIcon).show();
    } else {
        hideAllHeaderSort();
        $($ascIcon).show();
    }
    $($sortIcon).hide();
    $($sortIcon).show();
}

function hideAllHeaderSort() {
    $('.sortTableHeaderIcon').hide();
    $('.sortTableHeaderIcon2').show();
}

function setAsActiveSeason(event) {
    $('.fa-heart').removeClass().addClass('fa fa-heart-o');
    if ($(event.elem).hasClass('fa fa-heart')) {
        return;
    }
    $(event.elem).removeClass();
    $(event.elem).addClass('fa fa-refresh w3-spin');
    $.ajax({
        url: "../app/service/rest/season/season.php/" + event.sesonId,
        type: "PUT",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        statusCode: {
            200: function () {
                $(event.elem).removeClass();
                $(event.elem).addClass('fa fa-heart');
            }
        }
    });
}

function getSeasonIfno(val) {
    return val ? formatDouble(val) : '-';
}

function getSeasonDataOr_(val) {
    return val ? val : '-';
}