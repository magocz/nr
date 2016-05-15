<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

include_once '../../../business-components/season/repo/SeasonRepo.php';
include_once '../../../business-components/season/repo/SeasonBE.php';
include_once '../../../business-components/season/business-activity/SeasonBA.php';

include_once '../../../business-components/field/repo/FieldRepo.php';
include_once '../../../business-components/field/repo/FieldBE.php';
include_once '../../../business-components/field/business-activity/FieldBA.php';
include_once '../../../business-components/field/business-activity/FieldExcelGenerator.php';

include_once '../../../business-components/operation/repo/OperationRepo.php';
include_once '../../../business-components/operation/repo/OperationBE.php';

include_once '../../../business-components/other-costs/repo/OtherCostsRepo.php';
include_once '../../../business-components/other-costs/repo/OtherCostsBE.php';

include_once '../../../configs/db-config/db-config.php';
include_once '../../../business-components/common/SortUtil.php';
include_once '../../../business-components/season/SeasonBF.php';

/** Include PHPExcel */
require_once '../../../business-components/common/build/excel/PHPExcel.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$fieldBA = new FieldBA();

/// Create new PHPExcel object
if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $firstParam = array_shift($request);
        if (is_numeric($firstParam)) {
            $dt = new DateTime();
            $fileName = "field" . $firstParam . " at " . $dt->format('Y-m-d H:i:s') . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $fileName . '');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($fieldBA->generateFieldExcel($firstParam), 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
    }
}


