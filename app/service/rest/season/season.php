<?php
include_once '../../business-components/season/repo/SeasonRepo.php';
include_once '../../business-components/season/repo/SeasonBE.php';
include_once '../../business-components/season/business-activity/SeasonBA.php';

include_once '../../business-components/field/repo/FieldRepo.php';
include_once '../../business-components/field/repo/FieldBE.php';
include_once '../../business-components/field/business-activity/FieldBA.php';

include_once '../../business-components/operation/repo/OperationRepo.php';
include_once '../../business-components/operation/repo/OperationBE.php';
include_once '../../business-components/operation/business-activity/OperationBA.php';

include_once '../../business-components/other-costs/repo/OtherCostsRepo.php';
include_once '../../business-components/other-costs/repo/OtherCostsBE.php';

include_once '../../business-components/user/repo/UserRepo.php';

include_once '../../configs/db-config/db-config.php';
include_once '../../business-components/common/SortUtil.php';
include_once '../../business-components/season/SeasonBF.php';


session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

$seasonBF = new SeasonBF();

if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        header('Content-type: application/json');
        echo $seasonBF->findAllUserSeasons();
        exit;
    }
    if ($method == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data != null) {
            if (!$seasonBF->saveSeason($data)) {
                header('HTTP/1.0 500 Server error');
                exit;
            }
        }
    }
    if ($method == 'PUT') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            if (!$seasonBF->addSeasonAsActive($firstParam)) {
                header('HTTP/1.0 500 Server error');
                exit;
            }
        }
    }
    if ($method == 'DELETE') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            if (!$seasonBF->deleteSeason($firstParam)) {
                header('HTTP/1.0 500 Server error');
                exit;
            }
        }
    }
}