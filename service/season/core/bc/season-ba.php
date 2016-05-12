<?php
function findSeasonById($seasonId)
{
    return findSeasonById_DB($seasonId);
}

function findUserActiveSeason()
{
    return findSeasonById_DB($_SESSION['activeSeasonId']);
}

function findAllUserSeasons()
{
    return findAllUserSeasons_DB($_SESSION['id']);
}