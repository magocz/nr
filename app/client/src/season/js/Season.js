$(function ()  {
    $("#addSeasonModalContener").load("../app/client/src/season/html/add-season-modal.html");
    $("#seasonsCell").load("../app/client/src/season/html/seasons-table.html");

    generateSeasonsTable();
});