<?php
include_once '../../db-config/db-config.php';
include_once '../core/repo/field.php';
include_once '../../home/core/repo/done-operations.php';

include_once '../core/bc/field-data.php';


session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));


if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            header('Content-type: application/json');
            echo generateFieldData($firstParam);
        }
    }
}