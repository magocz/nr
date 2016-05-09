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
            'fieldNumber' => $field['FIELD_NR'],
            'description' => $field['DESCRIPTION'],
            'plant' => $field['PLANT'],
            'varietes' => $field['VARIETES'],
            'ha' => intval($field['HA']),
            'operationsNumber' => $field['OPERATIONS_NUMBER'],
        ));
    }
    return $homeData;
}