<?php

class SeasonBA
{

    private function generateFourtDrillDownCostChartDataModel($season)
    {
        $fourtDrildownChartData = (object)[];
        $fourtDrildownChartData->data = (object)[];
        $fourtDrildownChartData->data->costs = 0;
        $fourtDrildownChartData->data->seasonName = $season->name;
        $fourtDrildownChartData->data->seriesData = array();
        $fourtDrildownChartData->data->drilldownData = array();
        return $fourtDrildownChartData;
    }

    public  function getPlant_Varietes_Cost_Details_ProHa_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();
        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->totalCost;
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->totalCost;

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->totalCost;
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }
                if ($field->hasFieldCosts()) {

                    if (key_exists($field->plant, $drilldownHashMap)) {
                        array_push($drilldownHashMap[$field->plant]->data, (object)[
                            'name' => $field->varietes,
                            'y' => $field->totalCost,
                            'drilldown' => $field->varietes . 'allCostID',
                        ]);
                    } else {
                        @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                        @$drilldownHashMap[$field->plant]->name = $field->plant;
                        @$drilldownHashMap[$field->plant]->data = array(
                            (object)[
                                'name' => $field->varietes,
                                'y' => $field->totalCost,
                                'drilldown' => $field->varietes . 'allCostID',
                            ]
                        );
                    }

                    if (key_exists($field->varietes . 'allCostID', $drilldownHashMap)) {
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->totalFertilizerOperationsCost;
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->totalPlantProtectionOperationsCost;
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->totalOtherCosts;
                    } else {
                        @$drilldownHashMap[$field->varietes . 'allCostID']->id = $field->varietes . 'allCostID';
                        @$drilldownHashMap[$field->varietes . 'allCostID']->name = $field->varietes;
                        @$drilldownHashMap[$field->varietes . 'allCostID']->data = array(
                            (object)[
                                'name' => 'Koszty nawozów',
                                'y' => $field->totalFertilizerOperationsCost,
                                'drilldown' => $field->varietes . 'totalFertilizerOperationsCost',
                            ],
                            (object)[
                                'name' => 'Koszty ochrony roślin',
                                'y' => $field->totalPlantProtectionOperationsCost,
                                'drilldown' => $field->varietes . 'totalPlantProtectionOperationsCost',
                            ],
                            (object)[
                                'name' => 'Inne',
                                'y' => $field->totalOtherCosts,
                                'drilldown' => $field->varietes . 'totalOtherCosts',
                            ]
                        );
                    }
                    if (count($field->fertilizerOperations) != 0) {
                        if (key_exists($field->varietes . 'totalFertilizerOperationsCost', $drilldownHashMap)) {
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->id = $field->varietes . 'totalFertilizerOperationsCost';
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data = array();
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa),
                                ]);
                            }
                        }
                    }

                    if (count($field->plantProtectionOperations) != 0) {
                        if (key_exists($field->varietes . 'totalPlantProtectionOperationsCost', $drilldownHashMap)) {
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->id = $field->varietes . 'totalPlantProtectionOperationsCost';
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data = array();
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa),
                                ]);
                            }
                        }
                    }

                    if (count($field->otherCosts) != 0) {
                        if (key_exists($field->varietes . 'totalOtherCosts', $drilldownHashMap)) {
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost) / floatval($field->ha),
                                ]);
                            };

                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->id = $field->varietes . 'totalOtherCosts';
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->data = array();
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost) / floatval($field->ha),
                                ]);
                            };
                        }
                    }
                }
            }

            $fourtDrildownChartData->data->costs = $fourtDrildownChartData->data->costs / count($seriesHasMap); // sredni koszt na hektar

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function getPlant_Varietes_Cost_Details_ProField_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getTotalCostProField();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getTotalCostProField();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getTotalCostProField();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }
                if ($field->hasFieldCosts()) {

                    if (key_exists($field->plant, $drilldownHashMap)) {
                        array_push($drilldownHashMap[$field->plant]->data, (object)[
                            'name' => $field->varietes,
                            'y' => $field->getTotalCostProField(),
                            'drilldown' => $field->varietes . 'allCostID',
                        ]);
                    } else {
                        @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                        @$drilldownHashMap[$field->plant]->name = $field->plant;
                        @$drilldownHashMap[$field->plant]->data = array(
                            (object)[
                                'name' => $field->varietes,
                                'y' => $field->getTotalCostProField(),
                                'drilldown' => $field->varietes . 'allCostID',
                            ]
                        );
                    }

                    if (key_exists($field->varietes . 'allCostID', $drilldownHashMap)) {
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->getTotalFertilizerOperationsCostProField();
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->getTotalPlantProtectionOperationsCostProField();
                        $drilldownHashMap[$field->varietes . 'allCostID']->data[0]->y += $field->getTotalOtherCostProProField();
                    } else {
                        @$drilldownHashMap[$field->varietes . 'allCostID']->id = $field->varietes . 'allCostID';
                        @$drilldownHashMap[$field->varietes . 'allCostID']->name = $field->varietes;
                        @$drilldownHashMap[$field->varietes . 'allCostID']->data = array(
                            (object)[
                                'name' => 'Koszty nawozów',
                                'y' => $field->getTotalFertilizerOperationsCostProField(),
                                'drilldown' => $field->varietes . 'totalFertilizerOperationsCost',
                            ],
                            (object)[
                                'name' => 'Koszty ochrony roślin',
                                'y' => $field->getTotalPlantProtectionOperationsCostProField(),
                                'drilldown' => $field->varietes . 'totalPlantProtectionOperationsCost',
                            ],
                            (object)[
                                'name' => 'Inne',
                                'y' => $field->getTotalOtherCostProProField(),
                                'drilldown' => $field->varietes . 'totalOtherCosts',
                            ]
                        );
                    }
                    if (count($field->fertilizerOperations) != 0) {
                        if (key_exists($field->varietes . 'totalFertilizerOperationsCost', $drilldownHashMap)) {
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => $fertilizerOperation->costProHa * $field->ha * 1,
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->id = $field->varietes . 'totalFertilizerOperationsCost';
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data = array();
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => $fertilizerOperation->costProHa * $field->ha * 1,
                                ]);
                            }
                        }
                    }

                    if (count($field->plantProtectionOperations) != 0) {
                        if (key_exists($field->varietes . 'totalPlantProtectionOperationsCost', $drilldownHashMap)) {
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => $plantProtectionOperation->costProHa * $field->ha * 1,
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->id = $field->varietes . 'totalPlantProtectionOperationsCost';
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data = array();
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => $plantProtectionOperation->costProHa * $field->ha * 1,
                                ]);
                            }
                        }
                    }

                    if (count($field->otherCosts) != 0) {
                        if (key_exists($field->varietes . 'totalOtherCosts', $drilldownHashMap)) {
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => $otherCost->cost * 1,
                                ]);
                            };

                        } else {
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->id = $field->varietes . 'totalOtherCosts';
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->name = $field->varietes;
                            @$drilldownHashMap[$field->varietes . 'totalOtherCosts']->data = array();
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => $otherCost->cost * 1,
                                ]);
                            };
                        }
                    }
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }


    public  function getPlant_Varietes_Profit_Details_ProField_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getFieldProfit();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getFieldProfit();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getFieldProfit();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->varietes,
                        'y' => $field->getFieldProfit(),
                        'drilldown' => $field->varietes . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->varietes,
                            'y' => $field->getFieldProfit(),
                        ]
                    );
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function getPlant_Varietes_Profit_Details_ProHa_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getHaProfit();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getHaProfit();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getHaProfit();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->varietes,
                        'y' => $field->getHaProfit(),
                        'drilldown' => $field->varietes . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->varietes,
                            'y' => $field->getHaProfit(),
                        ]
                    );
                }
            }

            $fourtDrildownChartData->data->costs = $fourtDrildownChartData->data->costs / count($seriesHasMap); // zysk koszt na hektar

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }


    public  function getPlant_Varietes_Revenues_Details_ProField_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getFieldRevenues();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getFieldRevenues();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getFieldRevenues();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->varietes,
                        'y' => $field->getFieldRevenues(),
                        'drilldown' => $field->varietes . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->varietes,
                            'y' => $field->getFieldRevenues(),
                        ]
                    );
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function getPlant_Varietes_Revenues_Details_ProHa_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getHaRevenues();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getHaRevenues();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getHaRevenues();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->varietes,
                        'y' => $field->getHaRevenues(),
                        'drilldown' => $field->varietes . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->varietes,
                            'y' => $field->getHaRevenues(),
                        ]
                    );
                }
            }

            $fourtDrildownChartData->data->costs = $fourtDrildownChartData->data->costs / count($seriesHasMap); // sredni przychów na hektar

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }


    public  function getPlant_Varietes_FieldSize_Details_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->ha * 1;
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->ha * 1;

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->ha * 1;
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->varietes,
                        'y' => $field->ha * 1,
                        'drilldown' => $field->varietes . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->varietes,
                            'y' => $field->ha * 1,
                        ]
                    );
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function getPlant_Varietes_FieldNumber_Details_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->ha * 1;
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->ha * 1;

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->ha * 1;
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->fieldNumber,
                        'y' => $field->ha * 1,
                        'drilldown' => $field->fieldNumber . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->fieldNumber,
                            'y' => $field->ha * 1,
                        ]
                    );
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function getPlant_Varietes_FieldDescription_Details_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->ha * 1;
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->ha * 1;

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->ha * 1;
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'ID';
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->description,
                        'y' => $field->ha * 1,
                        'drilldown' => $field->description . 'allCostID',
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . 'ID';
                    @$drilldownHashMap[$field->plant]->name = $field->plant;
                    @$drilldownHashMap[$field->plant]->data = array(
                        (object)[
                            'name' => $field->description,
                            'y' => $field->ha * 1,
                        ]
                    );
                }
            }

            usort($seriesHasMap, 'sortSeriesDataByY');
            foreach ($seriesHasMap as $seriesData) {
                array_push($fourtDrildownChartData->data->seriesData, $seriesData);
            }

            foreach ($drilldownHashMap as $drilldownData) {
                usort($drilldownData->data, 'sortSeriesDataByY');
                array_push($fourtDrildownChartData->data->drilldownData, $drilldownData);
            }

            return $fourtDrildownChartData;
        }
        return null;
    }

    public  function generateSeasonOverviewTable($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();

        if ($season) {
            foreach ($season->fields as $field) {
                array_push($seriesHasMap, $field->getFieldOverview());
            }
            return $seriesHasMap;
        }
        return (object)[];
    }

}