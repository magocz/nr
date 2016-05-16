function openAddSeasonModal() {
    $('#addSeasonModalHeader').text('Dodawanie sezonu');
    $('#addSeasonModal').modal();
}

function addSeason() {
    var season = {seasonName: $('#seasonNameSelector').val()};
    $.ajax({
        url: "../../../service/rest/season/season.php/",
        type: "POST",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(season),
        statusCode: {
            200: function () {
                $('#addSeasonModal').modal('toggle');
            }
        }
    });
}
