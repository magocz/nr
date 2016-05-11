<?php
function generateSeasonTableData($seasonId)
{
    return json_encode(fillHomeTableDataModel(findAllFieldBySeasonId($seasonId)));
}


function fillHomeTableDataModel($fields)
{
    $homeData = (object)[];
    $homeData->data = array();

    foreach ($fields as $field) {
        array_push($homeData->data, array(
            'id' => $field['ID'],
            'fieldNumber' => iconv("iso-8859-2", "utf-8", $field['FIELD_NR']),
            'description' => iconv("iso-8859-2", "utf-8", $field['DESCRIPTION']),
            'plant' => iconv("iso-8859-2", "utf-8", $field['PLANT']),
            'varietes' => iconv("iso-8859-2", "utf-8", $field['VARIETES']),
            'ha' => floatval($field['HA']),
            'operationsNumber' => floatval($field['OPERATIONS_NUMBER']),
            'plantPrice' => floatval($field['PLANT_PRICE']),
            'tonsProHa' => floatval($field['TONS_PRO_HA']),
            'otherCosts' => floatval($field['OTHER_COSTS']),
            'seasonId' => floatval($field['SEASON_ID']),
        ));
    }
    return $homeData;
}