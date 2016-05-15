<?php
include_once '../../db-config/db-config.php';
include_once '../core/repo/season.php';

include_once '../core/bc/season-ba.php';


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
            echo findSeasonById($firstParam);
        } else {
            echo findSeasonById();
        }
    }
    if ($method == 'POST') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data != null) {
                if (!updateField($firstParam, $data)) {
                    header('HTTP/1.0 500 Server error');
                    exit;
                }
            }
        }
    }
    if ($method == 'PUT') {
        $firstParam = array_shift($request);
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data != null) {
            if (!saveField($data, $_SESSION['activeSeasonId'])) {
                header('HTTP/1.0 500 Server error');
                exit;
            }
        }
    }
    if ($method == 'DELETE') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            if (!deleteField($firstParam)) {
                header('HTTP/1.0 500 Server error');
                exit;
            }
        }
    }
    if ($method == 'PUT') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            header('Content-type: application/json');
            echo generateFieldData($firstParam);
        }
    }
}