<?php


class SeasonBF
{
    private $seasoBA;

    /**
     * seasoBA constructor.
     */
    public function __construct()
    {
        $this->seasoBA = new SeasonBA();
    }


    public function generatePlantToVarietesChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_FieldSize_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_FieldSize_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToFieldNumberChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_FieldNumber_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_FieldNumber_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToFieldDescriptionChartData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_FieldDescription_Details_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_FieldDescription_Details_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesToCostTypeToCostDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Cost_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Cost_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesToCostTypeToCostDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Cost_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Cost_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesProfitDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Profit_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Profit_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesProfitDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Profit_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Profit_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function generatePlantToVarietesRevenuesDetails_ProField_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Revenues_Details_ProField_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Revenues_Details_ProField_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generatePlantToVarietesRevenuesDetails_ProHa_CharData($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->getPlant_Varietes_Revenues_Details_ProHa_ChartData($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->getPlant_Varietes_Revenues_Details_ProHa_ChartData($_SESSION['activeSeasonId'], $_SESSION['id']));
    }


    public function generateSeasonOverviewTable($seasonId)
    {
        if ($seasonId != null) {
            return json_encode($this->seasoBA->generateSeasonOverviewTable($seasonId, $_SESSION['id']));
        }
        return json_encode($this->seasoBA->generateSeasonOverviewTable($_SESSION['activeSeasonId'], $_SESSION['id']));
    }

    public function saveSeason($data)
    {
        $response = SeasonRepo::saveSeason($data['seasonName'], $_SESSION['id']);
        if ($response != false) {
            print_r($response);
            if (UserRepo::changeActiveSeason($response, $_SESSION['id'])) {
                $_SESSION['activeSeasonId'] = $response;
                return true;
            }
        }
        return false;
    }

    public function deleteSeason($firstParam)
    {
        return SeasonRepo::deleteSeason($firstParam, $_SESSION['id']);
    }

    public function findAllUserSeasons()
    {
        return json_encode(SeasonRepo::findAllUserSeasons($_SESSION['id']));
    }

    public function addSeasonAsActive($firstParam)
    {
        if (SeasonRepo::isThtatUserSeason($firstParam, $_SESSION['id'])) {
            if (UserRepo::changeActiveSeason($firstParam, $_SESSION['id'])) {
                $_SESSION['activeSeasonId'] = $firstParam;
                return true;
            }
            return false;
        }
        return false;
    }


}