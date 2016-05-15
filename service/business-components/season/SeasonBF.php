<?php


class SeasonBF
{

    public function generatePlantToVarietesChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_FieldSize_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode( SeasonBA::getPlant_Varietes_FieldSize_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToFieldNumberChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_FieldNumber_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_FieldNumber_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToFieldDescriptionChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_FieldDescription_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_FieldDescription_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesToCostTypeToCostDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Cost_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Cost_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesToCostTypeToCostDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Cost_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Cost_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesProfitDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Profit_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Profit_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesProfitDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Profit_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Profit_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesRevenuesDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Revenues_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Revenues_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesRevenuesDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::getPlant_Varietes_Revenues_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::getPlant_Varietes_Revenues_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generateSeasonOverviewTable($seasonId)
    {
        if ($seasonId != null) {
            return json_encode(SeasonBA::generateSeasonOverviewTable($seasonId, $_SESSION['id']));
        }
        return json_encode(SeasonBA::generateSeasonOverviewTable($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


}