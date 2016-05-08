<?php

// Method to get active season overwiew data
function generateActiveSeasonTableData($fields)
{
    $homeData = (object)[];
    $homeData->data = array();
    fillHomeTableDataModel($homeData, $fields);
    return json_encode($homeData);
}


function fillHomeTableDataModel($homeData, $fields)
{
    foreach ($fields as $field) {
        array_push($homeData->data, array(
            'id' => $field['ID'],
            'fieldNumber' => $field['FIELD_NR'],
            'description' => $field['DESCRIPTION'],
            'plant' => $field['PLANT'],
            'varietes' => $field['VARIETES'],
            'ha' => intval($field['HA']),
            'operationsNumber' => $field['OPERATIONS_NUMBER'],
       ));
    }
}


function generateActiveSeasonChartData_plants_to_fieldDescription($season, $fields)
{
    $homeData = generatePipeChartDataModel($season);
    getChartData($fields, $homeData, 'PLANT', 'DESCRIPTION');
    return json_encode($homeData);
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
        $homeData->activeSeasonChart->haCount += intval($field['HA']);
        $isAdded = false;

        if (array_key_exists($field[$seriesVariable], $seriesHasMap)) {
            $seriesHasMap[$field[$seriesVariable]][0]->y += intval($field['HA']);
            for ($i = 0; $i < count($drilldownHashMap[$field[$seriesVariable]][0]->data); $i++) {
                if (in_array($field[$drilldownVariable], $drilldownHashMap[$field[$seriesVariable]][0]->data[$i])) {
                    $drilldownHashMap[$field[$seriesVariable]][0]->data[$i][1] += intval($field['HA']);
                    $isAdded = true;
                    break;
                }
            }
            if (!$isAdded) {
                array_push($drilldownHashMap[$field[$seriesVariable]][0]->data, array($field[$drilldownVariable], intval($field['HA'])));
            }

        } else {
            $seriesHasMap[$field[$seriesVariable]] = array();
            array_push($seriesHasMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'y' => intval($field['HA']),
                'drilldown' => $field[$seriesVariable]
            ]);
            $drilldownHashMap[$field[$seriesVariable]] = array();
            array_push($drilldownHashMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'id' => $field[$seriesVariable],
                'data' => array(array($field[$drilldownVariable], intval($field['HA'])))
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
}


function generateActiveSeasonChartData_plants_to_fieldNumber($season, $fields)
{
    $homeData = generatePipeChartDataModel($season);
    getChartData($fields, $homeData, 'PLANT', 'FIELD_NR');
    return json_encode($homeData);
}

function generateActiveSeasonChartData_plants_to_variates($season, $fields)
{
    $homeData = generatePipeChartDataModel($season);
    getChartData($fields, $homeData, 'PLANT', 'VARIETES');
    return json_encode($homeData);
}


function generateActiveSeasonColumnChartData_plants_to_Cost($season, $fields, $dbcon)
{
    $homeData = generateColumnChartDataModel($season);
    getColumnChartData($fields, $homeData, 'PLANT', $dbcon);
    return json_encode($homeData);
}


function generateColumnChartDataModel($season)
{
    $homeData = (object)[];
    $homeData->activeSeasonChart = (object)[];
    $homeData->activeSeasonChart->cost = 0;
    $homeData->activeSeasonChart->seasonName = $season[0]['name'];
    $homeData->activeSeasonChart->seriesData = array();
    $homeData->activeSeasonChart->drilldownData = array();
    return $homeData;
}


function getColumnChartData($fields, $homeData, $seriesVariable, $dbcon)
{
    $seriesHasMap = array();
    $drilldownHashMap = array();

    foreach ($fields as $field) {

        $allDoneOperations = findAllDoneOperationsByFieldId($field['ID'], $dbcon);
        $fertilizerCost = getFertilizerCost($allDoneOperations); // koszt nawozu
        $plantProtectionCost = getPlantProtectionCost($allDoneOperations); // koszt ochrony roslin
        if (array_key_exists($field[$seriesVariable], $seriesHasMap)) {
            $seriesHasMap[$field[$seriesVariable]][0]->y += ($plantProtectionCost + $fertilizerCost);
            $drilldownHashMap[$field[$seriesVariable]][0]->data[0][1] += $plantProtectionCost;
            $drilldownHashMap[$field[$seriesVariable]][0]->data[1][1] += $fertilizerCost;

            $homeData->activeSeasonChart->cost += $plantProtectionCost + $fertilizerCost;

        } else {

            $homeData->activeSeasonChart->cost += $plantProtectionCost + $fertilizerCost;
            $seriesHasMap[$field[$seriesVariable]] = array();
            array_push($seriesHasMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'y' => ($plantProtectionCost + $fertilizerCost),
                'drilldown' => $field[$seriesVariable]
            ]);
            $drilldownHashMap[$field[$seriesVariable]] = array();

            array_push($drilldownHashMap[$field[$seriesVariable]], (object)[
                'name' => $field[$seriesVariable],
                'id' => $field[$seriesVariable],
                'data' => array(array('Koszt ochrony roślin', $plantProtectionCost), array('Koszt nawozów', $fertilizerCost))
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
}

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


function getFertilizerCost($allDoneOperations)
{
    $fertilizerCost = 0;
    foreach ($allDoneOperations as $operation) {
        if ($operation['MEANS_TYPE'] == "fertilizer") {
            $fertilizerCost += (intval($operation['COST']));
        }
    }
    return $fertilizerCost;
}

function getPlantProtectionCost($allDoneOperations)
{
    $plantProtectionCost = 0;
    foreach ($allDoneOperations as $operation) {
        if ($operation['MEANS_TYPE'] == "plantProtection") {
            $plantProtectionCost += (intval($operation['COST']));
        }
    }
    return $plantProtectionCost;
}
