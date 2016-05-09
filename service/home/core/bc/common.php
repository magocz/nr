<?php


// sort functions
function sortDrilldownDataByValue($item1, $item2)
{
    if ($item1[1] == $item2[1]) return 0;
    return ($item1[1] < $item2[1]) ? 1 : -1;
}

function sortSeriesDataByY($item1, $item2)
{
    if ($item1[0]->y == $item2[0]->y) return 0;
    return ($item1[0]->y < $item2[0]->y) ? 1 : -1;
}