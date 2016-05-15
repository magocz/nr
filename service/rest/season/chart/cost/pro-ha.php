<?php
include_once '../../../../business-components/season/repo/SeasonRepo.php';
include_once '../../../../business-components/season/repo/SeasonBE.php';
include_once '../../../../business-components/season/business-activity/SeasonBA.php';

include_once '../../../../business-components/field/repo/FieldRepo.php';
include_once '../../../../business-components/field/repo/FieldBE.php';

include_once '../../../../business-components/operation/repo/OperationRepo.php';
include_once '../../../../business-components/operation/repo/OperationBE.php';

include_once '../../../../business-components/other-costs/repo/OtherCostsRepo.php';
include_once '../../../../business-components/other-costs/repo/OtherCostsBE.php';

include_once '../../../../configs/db-config/db-config.php';
include_once '../../../../business-components/common/SortUtil.php';
include_once '../../../../business-components/season/SeasonBF.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

$seasonBF = new SeasonBF(); // the BF of seasons

if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $firstParam = array_shift($request);
        header('Content-type: application/json');
        if (is_numeric($firstParam)) {
            echo $seasonBF->generatePlantToVarietesToCostTypeToCostDetails_ProHa_CharData($firstParam);
        } else {
            echo $seasonBF->generatePlantToVarietesToCostTypeToCostDetails_ProHa_CharData(null);
        }
    } else {
        header('HTTP/1.0 405 Method not allowed');
    }
}