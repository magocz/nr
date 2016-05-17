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

if ($_SESSION['id'] == null) {
    if ($method == 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $mail = $_POST['mail'];
        $response = $userBF->register($login, $mail, $password);
        if ($response == 1) {
            header("HTTP/1.0 201 Created");
            exit;

        } else if ($response == -1) {
            header("HTTP/1.0 401 MailError");
            exit;
        } else if ($response == 0) {
            header("HTTP/1.0 401 LoginError");
            exit;
        }
    }
} else {
    if ($method == 'GET') {
        header('Content-type: application/json');
        echo $userBF->findUser();
        exit;
    }
    if ($method == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data != null) {
            if (!$userBF->update($data)) {
                header('HTTP/1.0 500 Server error');
                exit;
            } else {

            }
        }
    }
}