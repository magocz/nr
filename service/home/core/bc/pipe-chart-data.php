<?php
function generateActiveSeasonChartData_plants_to_varietes($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getChartData(findAllFieldBySeasonId($seasonId), generatePipeChartDataModel($season), 'PLANT', 'VARIETES'));
    }
    return json_encode(null);
}

function generateActiveSeasonChartData_plants_to_fieldNumber($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getChartData(findAllFieldBySeasonId($seasonId), generatePipeChartDataModel($season), 'PLANT', 'FIELD_NR'));
    }
    return json_encode(null);
}


function generateChartData_plants_to_fieldDescription($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getChartData(findAllFieldBySeasonId($seasonId), generatePipeChartDataModel($season), 'PLANT', 'DESCRIPTION'));
    }
    return json_encode(null);
}

function generatePipeChartDataModel($season)
{
    $homeData = (object)[];
    $homeData->activeSeasonChart = (object)[];
    $homeData->activeSeasonChart->haCount = 0;
    $homeData->activeSeasonChart->seasonName = $season[0]['name'];
    $homeData->activeSeasonChart->seriesData = array();
    $homeData->activeSeasonChart->drilldownData = array();
    return $homeData;
}


function getChartData($fields, $homeData, $seriesVariable, $drilldownVariable)
{
    $seriesHasMap = array();
    $drilldownHashMap = array();

    foreach ($fields as $field) {
        $homeData->activeSeasonChart->haCount += floatval($field['HA']);
        $isAdded = false;

        if (array_key_exists($field[$seriesVariable], $seriesHasMap)) {
            $seriesHasMap[$field[$seriesVariable]][0]->y += floatval($field['HA']);
            for ($i = 0; $i < count($drilldownHashMap[$field[$seriesVariable]][0]->data); $i++) {
                if (in_array($field[$drilldownVariable], $drilldownHashMap[$field[$seriesVariable]][0]->data[$i])) {
                    $drilldownHashMap[$field[$seriesVariable]][0]->data[$i][1] += floatval($field['HA']);
                    $isAdded = true;
                    break;
                }
            }
            if (!$isAdded) {
                array_push($drilldownHashMap[$field[$seriesVariable]][0]->data, array(iconv("iso-8859-2", "utf-8",$field[$drilldownVariable]), floatval($field['HA'])));
            }

        } else {
            $seriesHasMap[$field[$seriesVariable]] = array();
            array_push($seriesHasMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'y' => floatval($field['HA']),
                'drilldown' => $field[$seriesVariable]
            ]);
            $drilldownHashMap[$field[$seriesVariable]] = array();
            array_push($drilldownHashMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'id' => $field[$seriesVariable],
                'data' => array(array(iconv("iso-8859-2", "utf-8",$field[$drilldownVariable]), floatval($field['HA'])))
            ]);

        }
    }

    foreach ($drilldownHashMap as $drilldownData) {
        usort($drilldownData[0]->data, 'sortDrilldownDataByValue');
        array_push($homeData->activeSeasonChart->drilldownData, $drilldownData[0]);
    }
    usort($seriesHasMap, 'sortSeriesDataByY');
    foreach ($seriesHasMap as $seriesData) {
        array_push($homeData->activeSeasonChart->seriesData, $seriesData[0]);
    }

    return $homeData;
}