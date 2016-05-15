<?php


class OperationBA
{
    public function addNewOperation($operationData){
        return OperationRepo::saveNewOperation($operationData, $_SESSION['activeSeasonId'], $_SESSION['id']);
    }

}