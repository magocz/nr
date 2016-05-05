<?php

include_once "../core/season.php";
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
                    echo generateHomeDataJSON($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }else{
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateHomeDataJSON($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        }elseif ($firstParam == 'fiedl-desctiption-to-plants') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateHomeDataJSONFieldDescriptionToPlants($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }else{
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateHomeDataJSONFieldDescriptionToPlants($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }
        }elseif ($firstParam == 'fieldnr-to-plants') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateHomeDataJSONFieldNrToPlants($season, $fields);
                    exit;
                } else {
                    header("HTTP/1.0 404 Not Found");
                    exit;
                }
            }else{
                $seasonId = $_SESSION['activeSeasonId'];
                $season = findSeasonById($seasonId, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seasonId, $dbcon);
                    header('Content-type: application/json');
                    echo generateHomeDataJSONFieldNrToPlants($season, $fields);
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


function generateHomeDataJSONFieldNrToPlants($season, $fields)
{
    $homeData = (object)[];
    $homeData->activeSeasonChart = (object)[];
    $homeData->activeSeasonChart->haCount = 0;
    $homeData->activeSeasonChart->seasonName = $season[0]['name'];
    $homeData->activeSeasonChart->seriesData = array();
    $homeData->activeSeasonChart->drilldownData = array();
    $homeData->activeSeasonTable = array();
    $homeData->activeSeasonLastFieldOperation = (object)[];
    getFieldNrToPlantChartData($fields, $homeData);
    return json_encode($homeData);
}


function generateHomeDataJSONFieldDescriptionToPlants($season, $fields)
{
    $homeData = (object)[];
    $homeData->activeSeasonChart = (object)[];
    $homeData->activeSeasonChart->haCount = 0;
    $homeData->activeSeasonChart->seasonName = $season[0]['name'];
    $homeData->activeSeasonChart->seriesData = array();
    $homeData->activeSeasonChart->drilldownData = array();
    $homeData->activeSeasonTable = array();
    $homeData->activeSeasonLastFieldOperation = (object)[];
    getFieldPlantToDescriptionChartData($fields, $homeData);
    return json_encode($homeData);
}

function generateHomeDataJSON($season, $fields)
{
    $homeData = (object)[];
    $homeData->activeSeasonChart = (object)[];
    $homeData->activeSeasonChart->haCount = 0;
    $homeData->activeSeasonChart->seasonName = $season[0]['name'];
    $homeData->activeSeasonChart->seriesData = array();
    $homeData->activeSeasonChart->drilldownData = array();
    $homeData->activeSeasonTable = array();
    $homeData->activeSeasonLastFieldOperation = (object)[];
    getFieldPlantToVariatesChartData($fields, $homeData);
    return json_encode($homeData);
}

function getFieldPlantToDescriptionChartData($fields, $homeData)
{

    $seriesHasMap = array();
    $drilldownHashMap = array();

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
        $homeData->activeSeasonChart->haCount += intval($field['HA']);

        if (array_key_exists($field['PLANT'], $seriesHasMap)) {
            $seriesHasMap[$field['PLANT']][0]->y += intval($field['HA']);
            array_push($drilldownHashMap[$field['PLANT']][0]->data, array($field['DESCRIPTION'], intval($field['HA'])));
        } else {
            $seriesHasMap[$field['PLANT']] = array();
            array_push($seriesHasMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'y' => intval($field['HA']),
                'drilldown' => $field['PLANT']
            ]);
            $drilldownHashMap[$field['PLANT']] = array();
            array_push($drilldownHashMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'id' => $field['PLANT'],
                'data' => array(array($field['DESCRIPTION'], intval($field['HA'])))
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


function getFieldPlantToVariatesChartData($fields, $homeData)
{

    $seriesHasMap = array();
    $drilldownHashMap = array();

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
        $homeData->activeSeasonChart->haCount += intval($field['HA']);

        $isAdded = false;

        if (array_key_exists($field['PLANT'], $seriesHasMap)) {
            $seriesHasMap[$field['PLANT']][0]->y += intval($field['HA']);
            for ($i = 0; $i < count($drilldownHashMap[$field['PLANT']][0]->data); $i++) {
                if (in_array($field['VARIETES'], $drilldownHashMap[$field['PLANT']][0]->data[$i])) {
                    $drilldownHashMap[$field['PLANT']][0]->data[$i][1] += intval($field['HA']);
                    $isAdded = true;
                    break;
                }
            }
            if (!$isAdded) {
                array_push($drilldownHashMap[$field['PLANT']][0]->data, array($field['VARIETES'], intval($field['HA'])));
            }

        } else {
            $seriesHasMap[$field['PLANT']] = array();
            array_push($seriesHasMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'y' => intval($field['HA']),
                'drilldown' => $field['PLANT']
            ]);
            $drilldownHashMap[$field['PLANT']] = array();
            array_push($drilldownHashMap[$field['PLANT']], (object)[
                'name' => $field['PLANT'],
                'id' => $field['PLANT'],
                'data' => array(array($field['VARIETES'], intval($field['HA'])))
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

function getFieldNrToPlantChartData($fields, $homeData)
{

    $seriesHasMap = array();
    $drilldownHashMap = array();

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
        $homeData->activeSeasonChart->haCount += intval($field['HA']);

        if (array_key_exists($field['FIELD_NR'], $seriesHasMap)) {
            $seriesHasMap[$field['FIELD_NR']][0]->y += intval($field['HA']);
            array_push($drilldownHashMap[$field['FIELD_NR']][0]->data, array($field['PLANT'], intval($field['HA'])));
        } else {
            $seriesHasMap[$field['FIELD_NR']] = array();
            array_push($seriesHasMap[$field['FIELD_NR']], (object)[
                'name' => $field['FIELD_NR'],
                'y' => intval($field['HA']),
                'drilldown' => $field['FIELD_NR']
            ]);
            $drilldownHashMap[$field['FIELD_NR']] = array();
            array_push($drilldownHashMap[$field['FIELD_NR']], (object)[
                'name' => $field['FIELD_NR'],
                'id' => $field['FIELD_NR'],
                'data' => array(array($field['PLANT'], intval($field['HA'])))
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