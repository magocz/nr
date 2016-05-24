<?php
include_once '../../../business-components/season/repo/SeasonRepo.php';
include_once '../../../business-components/season/repo/SeasonBE.php';
include_once '../../../business-components/season/business-activity/SeasonBA.php';

include_once '../../../business-components/field/repo/FieldRepo.php';
include_once '../../../business-components/field/repo/FieldBE.php';

include_once '../../../business-components/operation/repo/OperationRepo.php';
include_once '../../../business-components/operation/repo/OperationBE.php';

include_once '../../../business-components/other-costs/repo/OtherCostsRepo.php';
include_once '../../../business-components/other-costs/repo/OtherCostsBE.php';

include_once '../../../configs/db-config/db-config.php';
include_once '../../../business-components/common/SortUtil.php';
include_once '../../../business-components/season/SeasonBF.php';


$seasonBF = new SeasonBF(); // the BF of seasons


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
            echo $seasonBF->generateSeasonOverviewTable($firstParam);
        } else {
            header('Content-type: application/json');
            echo $seasonBF->generateSeasonOverviewTable($_SESSION['activeSeasonId']);
        }
    }
}