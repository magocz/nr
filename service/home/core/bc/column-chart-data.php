<?php

function generateActiveSeasonColumnChartData_plants_to_operationsCost($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getAllOperationsCostChartData($seasonId, generateColumnChartDataModel($season)));
    }
    return json_encode(null);
}

function generateActiveSeasonColumnChartData_plants_to_profit($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getPlantProfitChartData($seasonId, generateColumnChartDataModel($season)));
    }
    return json_encode(null);
}

function generateActiveSeasonColumnChartData_plants_to_proceeds($seasonId)
{
    $season = findSeasonById($seasonId);
    if (count($season) == 1) {
        return json_encode(getPlantProceedsChartData($seasonId, generateColumnChartDataModel($season)));
    }
    return json_encode(null);
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


function getAllOperationsCostChartData($seasonId, $homeData)
{
    $seriesHasMap = array();
    $drilldownHashMap = array();
    $fields = findAllFieldBySeasonId($seasonId);
    $allDoneOperations = findAllDoneOperationsBySeasonId($seasonId);

    foreach ($fields as $field) {
        $fertilizerCost = getFertilizerCost($allDoneOperations, $field['ID']); // koszt nawozu
        $plantProtectionCost = getPlantProtectionCost($allDoneOperations, $field['ID']); // koszt ochrony roslin
        if (array_key_exists($field['PLANT'], $seriesHasMap)) {
            $seriesHasMap[$field['PLANT']][0]->y += ($plantProtectionCost + $fertilizerCost) * $field['HA'];
            $drilldownHashMap[$field['PLANT']][0]->data[0][1] += $plantProtectionCost * $field['HA'];
            $drilldownHashMap[$field['PLANT']][0]->data[1][1] += $fertilizerCost * $field['HA'];

            $homeData->activeSeasonChart->cost += ($plantProtectionCost + $fertilizerCost) * $field['HA'];

        } else {

            $homeData->activeSeasonChart->cost += ($plantProtectionCost + $fertilizerCost) * $field['HA'];
            $seriesHasMap[$field['PLANT']] = array();
            array_push($seriesHasMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'y' => ($plantProtectionCost + $fertilizerCost) * $field['HA'],
                'drilldown' => $field['PLANT']
            ]);
            $drilldownHashMap[$field['PLANT']] = array();

            array_push($drilldownHashMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'id' => $field['PLANT'],
                'data' => array(array('Koszt ochrony roślin', ($plantProtectionCost * $field['HA'])), array('Koszt nawozów', ($fertilizerCost * $field['HA'])))
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

function getPlantProceedsChartData($seasonId, $homeData)
{
    $seriesHasMap = array();
    $drilldownHashMap = array();
    $fields = findAllFieldBySeasonId($seasonId);

    foreach ($fields as $field) {

        $plantProfit = $field['PLANT_PRICE'] * $field['TONS_PRO_HA'] * $field['HA'];

        if (array_key_exists($field['PLANT'], $seriesHasMap)) {
            $seriesHasMap[$field['PLANT']][0]->y += $plantProfit;
            $homeData->activeSeasonChart->cost += $plantProfit;
        } else {
            $homeData->activeSeasonChart->cost += $plantProfit;
            $seriesHasMap[$field['PLANT']] = array();
            array_push($seriesHasMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'y' => ($plantProfit),
                'drilldown' => $field['PLANT']
            ]);
            $drilldownHashMap[$field['PLANT']] = array();
        }

    }
    foreach ($drilldownHashMap as $drilldownData) {
        array_push($homeData->activeSeasonChart->drilldownData, $drilldownData);
    }

    usort($seriesHasMap, 'sortSeriesDataByY');
    foreach ($seriesHasMap as $seriesData) {
        array_push($homeData->activeSeasonChart->seriesData, $seriesData[0]);
    }
    return $homeData;
}


function getPlantProfitChartData($seasonId, $homeData)
{
    $seriesHasMap = array();
    $drilldownHashMap = array();
    $fields = findAllFieldBySeasonId($seasonId);
    $allDoneOperations = findAllDoneOperationsBySeasonId($seasonId);

    foreach ($fields as $field) {
        $fertilizerCost = getFertilizerCost($allDoneOperations, $field['ID']); // koszt nawozu
        $plantProtectionCost = getPlantProtectionCost($allDoneOperations, $field['ID']); // koszt ochrony roslin
        $plantProfit = $field['PLANT_PRICE'] * $field['TONS_PRO_HA'] * $field['HA'];

        if (array_key_exists($field['PLANT'], $seriesHasMap)) {
            $seriesHasMap[$field['PLANT']][0]->y += $plantProfit - ($plantProtectionCost + $fertilizerCost) * $field['HA'];
            $homeData->activeSeasonChart->cost += $plantProfit - ($plantProtectionCost + $fertilizerCost) * $field['HA'];
        } else {
            $homeData->activeSeasonChart->cost += $plantProfit - ($plantProtectionCost + $fertilizerCost) * $field['HA'];
            $seriesHasMap[$field['PLANT']] = array();
            array_push($seriesHasMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'y' => ($plantProfit - ($plantProtectionCost + $fertilizerCost) * $field['HA']),
                'drilldown' => $field['PLANT']
            ]);
            $drilldownHashMap[$field['PLANT']] = array();
        }

    }
    foreach ($drilldownHashMap as $drilldownData) {
        array_push($homeData->activeSeasonChart->drilldownData, $drilldownData);
    }

    usort($seriesHasMap, 'sortSeriesDataByY');
    foreach ($seriesHasMap as $seriesData) {
        array_push($homeData->activeSeasonChart->seriesData, $seriesData[0]);
    }
    return $homeData;
}


function getFertilizerCost($allDoneOperations, $fieldId)
{
    $fertilizerCost = 0;
    foreach ($allDoneOperations as $operation) {
        if ($operation['MEANS_TYPE'] == "fertilizer" && $operation['FIELD_ID'] == $fieldId) {
            $fertilizerCost += (intval($operation['COST_PRO_HA']));
        }
    }
    return $fertilizerCost;
}

function getPlantProtectionCost($allDoneOperations, $fieldId)
{
    $plantProtectionCost = 0;
    foreach ($allDoneOperations as $operation) {
        if ($operation['MEANS_TYPE'] == "plantProtection" && $operation['FIELD_ID'] == $fieldId) {
            $plantProtectionCost += (intval($operation['COST_PRO_HA']));
        }
    }
    return $plantProtectionCost;
}