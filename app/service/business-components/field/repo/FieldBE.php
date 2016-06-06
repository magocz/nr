<?php


class FieldBE
{
    // DB field - columns
    public $id;
    public $fieldNumber;
    public $seasonId;
    public $userId;
    public $description;
    public $plant;
    public $varietes;
    public $ha;
    public $operationsNumber;
    public $plantPrice;
    public $tonsProHa;
    public $fertilizerOperations = array();
    public $plantProtectionOperations = array();
    public $otherCosts = array();

    // Added hepl variables

    public $totalCost = 0;
    public $totalFertilizerOperationsCost = 0;
    public $totalPlantProtectionOperationsCost = 0;
    public $totalOtherCosts = 0;

    /**
     * FieldBE constructor.
     */
    public function     __construct($fieldDB, $operationsDB, $otherCostsDB)
    {
        $this->id = $fieldDB['ID'];
        $this->fieldNumber = $fieldDB['FIELD_NR'];
        $this->seasonId = $fieldDB['SEASON_ID'];
        $this->userId = $fieldDB['USER_ID'];
        $this->description = $fieldDB['DESCRIPTION'];
        $this->plant = $fieldDB['PLANT'];
        $this->varietes = $fieldDB['VARIETES'];
        $this->ha = $fieldDB['HA'];
        $this->operationsNumber = $fieldDB['OPERATIONS_NUMBER'];
        $this->plantPrice = $fieldDB['PLANT_PRICE'];
        $this->tonsProHa = $fieldDB['TONS_PRO_HA'];


        foreach ($operationsDB as $operation) {
            if ($operation['MEANS_TYPE'] == 'plantProtection') {
                array_push($this->plantProtectionOperations, new OperationBE($operation));
                $this->totalPlantProtectionOperationsCost += $operation['COST_PRO_HA'];
            } else if ($operation['MEANS_TYPE'] == 'fertilizer') {
                array_push($this->fertilizerOperations, new OperationBE($operation));
                $this->totalFertilizerOperationsCost += $operation['COST_PRO_HA'];
            }
            $this->totalCost += ($operation['COST_PRO_HA']) * $this->ha;// count for field so * ha

        }

        foreach ($otherCostsDB as $otherCosts) {
            array_push($this->otherCosts, new OtherCostsBE($otherCosts));
            $this->totalCost += $otherCosts['COST'];
            $this->totalOtherCosts += $otherCosts['COST'];
        }
    }

    public function setOperations($operationsDB, $otherCostsDB){

        foreach ($operationsDB as $operation) {
            if ($operation['MEANS_TYPE'] == 'plantProtection') {
                array_push($this->plantProtectionOperations, new OperationBE($operation));
                $this->totalPlantProtectionOperationsCost += $operation['COST_PRO_HA'];
            } else if ($operation['MEANS_TYPE'] == 'fertilizer') {
                array_push($this->fertilizerOperations, new OperationBE($operation));
                $this->totalFertilizerOperationsCost += $operation['COST_PRO_HA'];
            }
            $this->totalCost += ($operation['COST_PRO_HA']);// count for field so * ha

        }

        foreach ($otherCostsDB as $otherCosts) {
            array_push($this->otherCosts, new OtherCostsBE($otherCosts));
            $this->totalCost += $otherCosts['COST'];
            $this->totalOtherCosts += $otherCosts['COST'];
        }
    }

    public function getFieldRevenues()
    {
        return $this->ha * $this->tonsProHa * $this->plantPrice;
    }


    public function getHaRevenues()
    {
        return $this->tonsProHa * $this->plantPrice;
    }


    public function getFieldProfit()
    {
        return $this->getFieldRevenues() - $this->getTotalCostProField();
    }

    public function getHaProfit()
    {
        return $this->getHaRevenues() - $this->getTotalCostProHa();
    }

    public function getTotalCostProField()
    {
        return $this->totalCost * $this->ha;
    }

    public function getTotalCostProHa()
    {
        return $this->totalCost;
    }

    public function getTotalFertilizerOperationsCostProHa()
    {
        return $this->totalFertilizerOperationsCost;
    }

    public function getTotalFertilizerOperationsCostProField()
    {
        return $this->totalFertilizerOperationsCost * $this->ha;
    }

    public function getTotalPlantProtectionOperationsCostProHa()
    {
        return $this->totalPlantProtectionOperationsCost;
    }

    public function getTotalPlantProtectionOperationsCostProField()
    {
        return $this->totalPlantProtectionOperationsCost * $this->ha;
    }

    public function getTotalOtherCostProHa()
    {
        return $this->totalOtherCosts / $this->ha;
    }

    public function getTotalOtherCostProProField()
    {
        return $this->totalOtherCosts;
    }

    public function hasFieldCosts()
    {
        return $this->totalCost != 0;
    }

    public function getFieldOverview()
    {
        return (object)[
            'id' => $this->id,
            'fieldNumber' => $this->fieldNumber,
            'description' => $this->description,
            'plant' => $this->plant,
            'varietes' => $this->varietes,
            'ha' => $this->ha,
            'operationsNumber' => $this->operationsNumber,
            'plantPrice' => $this->plantPrice,
            'tonsProHa' => $this->tonsProHa,
            'seasonId' => $this->id,
        ];
    }

}