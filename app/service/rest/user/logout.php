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
include_once '../../business-components/user/business-activity/UserBA.php';
include_once '../../business-components/user/UserBF.php';

include_once '../../configs/db-config/db-config.php';
include_once '../../business-components/common/SortUtil.php';
include_once '../../business-components/season/SeasonBF.php';


session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

$userBF = new UserBF();

if ($method == 'GET') {
    if ($userBF->logout()) {
        header("HTTP/1.0 200 OK");
        exit;
    }
    header("HTTP/1.0 500 Logout Error");
    exit;
} else {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

