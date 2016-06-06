<?php


class OtherCostsBA
{

    public function generateOtherCosts($firstParam)
    {
        return json_encode(OtherCostsRepo::findAllOtherCostsByFieldId($firstParam, $_SESSION['id']));
    }

    public function addNewOtherCost($data)
    {
        return OtherCostsRepo::saveNewOtherCost($data, $_SESSION['activeSeasonId'], $_SESSION['id']);
    }

    public function deleteOtherCost($firstParam)
    {
        return OtherCostsRepo::deleteOtherCostById($firstParam, $_SESSION['id']);
    }

    public function updateOtherCost($data, $firstParam)
    {
        return OtherCostsRepo::updateOtherCostById($data,$firstParam, $_SESSION['id']);
    }
}