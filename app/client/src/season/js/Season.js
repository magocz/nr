$(function ()  {
    $("#addSeasonModalContener").load("../../src/season/html/add-season-modal.html");
    $("#seasonsCell").load("../../src/season/html/seasons-table.html");

    generateSeasonsTable();
});