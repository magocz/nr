<?php

header('Content-Type: text/html; charset=utf-8');

include_once "../core/season.php";
include_once "../core/done-operations.php";
include_once "../db-config/db-config.php";


session_start();

$method = $_SERVER['REQUEST_METHOD'];

if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            $season = findSeasonById($firstParam, $dbcon);
            if (count($season) != 0) {
                header('Content-type: application/json');
                echo json_encode($season);
            } else {
                header("HTTP/1.0 404 Not Found");
            }
        } else if ($firstParam == 'find-all') {
            $seasons = findSeasonsByUserId($dbcon);
            header('Content-type: application/json');
            echo json_encode($seasons);
        } elseif ($firstParam == 'plants-to-varietes') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_variates($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            } else {
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_variates($season, $fields, $dbcon);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        } elseif ($firstParam == 'plants-to-description') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_fieldDescription($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            } else {
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_fieldDescription($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        } elseif ($firstParam == 'plants-to-field') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_fieldNumber($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            } else {
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonChartData_plants_to_fieldNumber($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        } elseif ($firstParam == 'cost-to-plants') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonColumnChartData_plants_to_Cost($season, $fields, $dbcon);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            } else {
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonColumnChartData_plants_to_Cost($season, $fields, $dbcon);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        }
    } elseif ($method == 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (count($input) == 3) {
            echo "god!";
            $description = $input['description'];
            $startDate = $input['startDate'];
            $stopDate = $input['stopDate'];
            if ($description != null && $startDate != null && $stopDate != null) {
                if (save($description, $startDate, $stopDate, $dbcon)) {
                    header("HTTP/1.0 201 Created");
                    return;
                }
            }
            header("HTTP/1.0 400 Bad Request");
        }

    } else {
        header("HTTP/1.0 405 Method Not Allowed");
        exit;
    }
}


function generateActiveSeasonTableData($fields)
{
    $homeData = generateHomeTableDataModel();
    fillHomeTableDataModel($homeData, $fields);
    return json_encode($homeData);
}


function generateHomeTableDataModel()
{
    $homeData = (object)[];
    $homeData->activeSeasonTable = array();
    return json_encode($homeData);
}

function fillHomeTableDataModel($homeData, $fields)
{
    foreach ($fields as $field) {
        array_push($homeData->activeSeasonTable, (object)[
            'id' => $field['ID'],
            'fieldNumber' => $field['FIELD_NR'],
            'description' => $field['DESCRIPTION'],
            'plant' => $field['PLANT'],
            'varietes' => $field['VARIETES'],
            'ha' => intval($field['HA']),
            'operationsNumber' => $field['OPERATIONS_NUMBER']
        ]);
    }
}

function generateActiveSeasonChartData_plants_to_fieldDescription($season, $fields)
{
    $homeData = generatePipeChartDataModel($season);
    getChartData($fields, $homeData, 'PLANT', 'DESCRIPTION');
    return json_encode($homeData);
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
        array_push($homeData->activeSeasonChart->drilldownData, $drilldownData[0]);
    }

    foreach ($seriesHasMap as $seriesData) {
        array_push($homeData->activeSeasonChart->seriesData, $seriesData[0]);
    }
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
            $fertilizerCost = getFertilizerCost($allDoneOperations); // koszt nawozu
            $plantProtectionCost = getPlantProtectionCost($allDoneOperations); // koszt ochrony roslin

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


    krsort($seriesHasMap);
    foreach ($drilldownHashMap as $drilldownData) {
        usort($drilldownData[0]->data,'invenDescSort');
        array_push($homeData->activeSeasonChart->drilldownData, $drilldownData[0]);
    }

    foreach ($seriesHasMap as $seriesData) {
        array_push($homeData->activeSeasonChart->seriesData, $seriesData[0]);
    }
}

function invenDescSort($item1, $item2)
{
    if ($item1[1] == $item2[1]) return 0;
    return ($item1[1] < $item2[1]) ? 1 : -1;
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