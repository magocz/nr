<?php


class OperationBA
{
    public function addNewOperation($operationData)
    {
        if (OperationRepo::saveNewOperation($operationData, $_SESSION['activeSeasonId'], $_SESSION['id'])) {
            return FieldRepo::addOperationNumber($operationData['fieldId'], $_SESSION['id']);
        }
        return false;
    }


    public function deleteOperation($operationData)
    {
        if (OperationRepo::deleteOperation($operationData['id'], $_SESSION['id'])) {
            return FieldRepo::deleteOperationNumber($operationData['fieldId'], $_SESSION['id']);
        }
        return false;
    }

    public function updateOperation($operationData)
    {
        return OperationRepo::updateOperation($operationData, $_SESSION['id']);
    }

}