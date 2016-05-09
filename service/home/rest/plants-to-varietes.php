<?php
include_once '../../db-config/db-config.php';
include_once '../core/repo/season.php';
include_once '../core/bc/pipe-chart-data.php';
include_once '../core/bc/common.php';


session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));


if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $firstParam = array_shift($request);
        header('Content-type: application/json');
        if (is_numeric($firstParam)) {
            echo generateActiveSeasonChartData_plants_to_varietes($firstParam);
        } else {
            echo generateActiveSeasonChartData_plants_to_varietes($_SESSION['activeSeasonId']);
        }
    }
}