<?php
function generateFieldData($fieldId)
{
    return json_encode(fillFieldDataModel($fieldId));
}


function fillFieldDataModel($fieldId)
{
    $field = findFieldById($fieldId, $_SESSION['id']);

    if (count($field) == 1) {
        $fieldData = (object)[];
        $fieldData->description = $field[0]['DESCRIPTION'];
        $fieldData->plant = $field[0]['PLANT'];
        $fieldData->varietes = $field[0]['VARIETES'];
        $fieldData->ha = $field[0]['HA'];
        $fieldData->operationNumber = $field[0]['OPERATIONS_NUMBER'];
        $fieldData->plantPrice = $field[0]['PLANT_PRICE'];
        $fieldData->tonsProHa = $field[0]['TONS_PRO_HA'];
        $fieldData->tons = $field[0]['TONS'];
        $fieldData->otherCost = $field[0]['OTHER_COSTS'];
        $fieldData->seasonId = $field[0]['SEASON_ID'];

        $fieldData->doneOperations = array();
        $allDoneOperations = findAllDoneOperationsByFieldId($fieldId);
        foreach ($allDoneOperations as $operation) {
            array_push($fieldData->doneOperations, (object)[
                'id' => $operation['ID'],
                'operationData' => $operation['DATA'],
                'meansName' => $operation['MEANS_NAME'],
                'meansType' => $operation['MEANS_TYPE'],
                'meansDoseIn_L_ProHa' => $operation['MEANS_DOSE_L_HA'],
                'meansDoseIn_KG_ProHa' => intval($operation['MEANS_DOSE_KG_HA']),
                'economicHarm' => $operation['ECONOMIC_HARN'],
                'cost' => $operation['COST'],
                'costProHa' => $operation['COST_PRO_HA'],
            ]);
        }
        return $fieldData;
    }
    return null;
}

function updateField($fieldId, $data)
{
    return updateFieldById($fieldId, $data, $_SESSION['id']);
}

function deleteField($fieldId)
{
    return deleteFieldById($fieldId, $_SESSION['id']);
}

function saveField($data, $seasonId)
{
    return saveNewField($data,$seasonId, $_SESSION['id']);
}
