<?php

header('Content-Type: text/html; charset=utf-8');

include_once "../core/season/repo/season-repo.php";
include_once "../core/season/season-ba.php";
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
        } elseif ($firstParam == 'home-table') {
            $seckondParam = array_shift($request);
            if (is_numeric($seckondParam)) {
                $season = findSeasonById($seckondParam, $dbcon);
                if (count($season) != 0) {
                    $fields = findAllFieldBySeasonId($seckondParam, $dbcon);
                    header('Content-type: application/json');
                    echo generateActiveSeasonTableData($fields);
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
                    echo generateActiveSeasonTableData($fields);
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