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

    public function getPlant_Varietes_Cost_Details_ProHa_ChartData($seasonId, $userId)
    {
        $season = SeasonRepo::findSeasonById($seasonId, $userId);
        $seriesHasMap = array();
        $drilldownHashMap = array();

        if ($season) {
            $fourtDrildownChartData = SeasonBA::generateFourtDrillDownCostChartDataModel($season);

            foreach ($season->fields as $field) {
                $fourtDrildownChartData->data->costs += $field->getTotalCostProHa();
                if (key_exists($field->plant, $seriesHasMap)) {
                    @$seriesHasMap[$field->plant]->y += $field->getTotalCostProHa();

                } else {
                    @$seriesHasMap[$field->plant]->y = $field->getTotalCostProHa();
                    @$seriesHasMap[$field->plant]->name = $field->plant;
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'allCostID';
                }
                if ($field->hasFieldCosts()) {
                    $isAdded = false;
                    if (key_exists($field->plant . 'allCostID', $drilldownHashMap)) {
                        for ($i = 0; $i < count($drilldownHashMap[$field->plant . 'allCostID']->data); $i++) {
                            if ($drilldownHashMap[$field->plant . 'allCostID']->data[$i]->name == $field->varietes) {
                                $drilldownHashMap[$field->plant . 'allCostID']->data[$i]->y += $field->getTotalCostProHa();
                                $isAdded = true;
                                break;
                            }
                        }
                        if (!$isAdded) {
                            array_push($drilldownHashMap[$field->plant . 'allCostID']->data,
                                (object)[
                                    'name' => $field->varietes,
                                    'y' => $field->getTotalCostProHa(),
                                    'drilldown' => $field->varietes . 'ID',
                                ]
                            );
                        }
                    } else {
                        @$drilldownHashMap[$field->plant . 'allCostID']->id = $field->plant . 'allCostID';
                        @$drilldownHashMap[$field->plant . 'allCostID']->name = $field->plant;
                        @$drilldownHashMap[$field->plant . 'allCostID']->data = array(
                            (object)[
                                'name' => $field->varietes,
                                'y' => $field->getTotalCostProHa(),
                                'drilldown' => $field->varietes . 'ID',
                            ]
                        );
                    }

                    if (key_exists($field->varietes . 'ID', $drilldownHashMap)) {
                        array_push($drilldownHashMap[$field->varietes . 'ID']->data, (object)[
                            'name' => $field->varietes . ': ' . $field->description,
                            'y' => $field->getTotalCostProHa(),
                            'drilldown' => $field->varietes . $field->id . 'allCostID',
                        ]);
                    } else {
                        @$drilldownHashMap[$field->varietes . 'ID']->id = $field->varietes . 'ID';
                        @$drilldownHashMap[$field->varietes . 'ID']->name = $field->plant;
                        @$drilldownHashMap[$field->varietes . 'ID']->data = array(
                            (object)[
                                'name' => $field->varietes . ': ' . $field->description,
                                'y' => $field->getTotalCostProHa(),
                                'drilldown' => $field->varietes . $field->id . 'allCostID',
                            ]
                        );
                    }


                    if (key_exists($field->varietes . $field->id . 'allCostID', $drilldownHashMap)) {
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalFertilizerOperationsCostProHa();
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalPlantProtectionOperationsCostProHa();
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalOtherCostProHa();
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->count++;
                    } else {
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->id = $field->varietes . $field->id . 'allCostID';
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->name = $field->varietes . ': ' . $field->description;
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->count = 1;
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data = array(
                            (object)[
                                'name' => 'Koszty nawozów',
                                'y' => $field->getTotalFertilizerOperationsCostProHa(),
                                'drilldown' => $field->varietes . $field->id . 'totalFertilizerOperationsCost',
                            ],
                            (object)[
                                'name' => 'Koszty ochrony roślin',
                                'y' => $field->getTotalPlantProtectionOperationsCostProHa(),
                                'drilldown' => $field->varietes . $field->id . 'totalPlantProtectionOperationsCost',
                            ],
                            (object)[
                                'name' => 'Inne',
                                'y' => $field->getTotalOtherCostProHa(),
                                'drilldown' => $field->varietes . $field->id . 'totalOtherCosts',
                            ]
                        );
                    }

                    if (count($field->fertilizerOperations) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalFertilizerOperationsCost', $drilldownHashMap)) {
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->id = $field->varietes . $field->id . 'totalFertilizerOperationsCost';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data = array();
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa),
                                ]);
                            }
                        }
                    }

                    if (count($field->plantProtectionOperations) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalPlantProtectionOperationsCost', $drilldownHashMap)) {
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . $field->id . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->id = $field->varietes . $field->id . 'totalPlantProtectionOperationsCost';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data = array();
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa),
                                ]);
                            }
                        }
                    }

                    if (count($field->otherCosts) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalOtherCosts', $drilldownHashMap)) {
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost),
                                ]);
                            };

                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->id = $field->varietes . $field->id . 'totalOtherCosts';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data = array();
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost),
                                ]);
                            };
                        }
                    }
                }
            }

            foreach ($season->fields as $field) {
                if($drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y != 0)
                $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y = $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0] / count($drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data);
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

    public
    function getPlant_Varietes_Cost_Details_ProField_ChartData($seasonId, $userId)
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
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . 'allCostID';
                }
                if ($field->hasFieldCosts()) {
                    $isAdded = false;
                    if (key_exists($field->plant . 'allCostID', $drilldownHashMap)) {
                        for ($i = 0; $i < count($drilldownHashMap[$field->plant . 'allCostID']->data); $i++) {
                            if ($drilldownHashMap[$field->plant . 'allCostID']->data[$i]->name == $field->varietes) {
                                $drilldownHashMap[$field->plant . 'allCostID']->data[$i]->y += $field->getTotalCostProField();
                                $isAdded = true;
                                break;
                            }
                        }
                        if (!$isAdded) {
                            array_push($drilldownHashMap[$field->plant . 'allCostID']->data,
                                (object)[
                                    'name' => $field->varietes,
                                    'y' => $field->getTotalCostProField(),
                                    'drilldown' => $field->varietes . 'ID',
                                ]
                            );
                        }
                    } else {
                        @$drilldownHashMap[$field->plant . 'allCostID']->id = $field->plant . 'allCostID';
                        @$drilldownHashMap[$field->plant . 'allCostID']->name = $field->plant;
                        @$drilldownHashMap[$field->plant . 'allCostID']->data = array(
                            (object)[
                                'name' => $field->varietes,
                                'y' => $field->getTotalCostProField(),
                                'drilldown' => $field->varietes . 'ID',
                            ]
                        );
                    }

                    if (key_exists($field->varietes . 'ID', $drilldownHashMap)) {
                        array_push($drilldownHashMap[$field->varietes . 'ID']->data, (object)[
                            'name' => $field->varietes . ': ' . $field->description,
                            'y' => $field->getTotalCostProField(),
                            'drilldown' => $field->varietes . $field->id . 'allCostID',
                        ]);
                    } else {
                        @$drilldownHashMap[$field->varietes . 'ID']->id = $field->varietes . 'ID';
                        @$drilldownHashMap[$field->varietes . 'ID']->name = $field->plant;
                        @$drilldownHashMap[$field->varietes . 'ID']->data = array(
                            (object)[
                                'name' => $field->varietes . ': ' . $field->description,
                                'y' => $field->getTotalCostProField(),
                                'drilldown' => $field->varietes . $field->id . 'allCostID',
                            ]
                        );
                    }


                    if (key_exists($field->varietes . $field->id . 'allCostID', $drilldownHashMap)) {
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalFertilizerOperationsCostProField();
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalPlantProtectionOperationsCostProField();
                        $drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data[0]->y += $field->getTotalOtherCostProProField();
                    } else {
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->id = $field->varietes . $field->id . 'allCostID';
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->name = $field->varietes . ': ' . $field->description;
                        @$drilldownHashMap[$field->varietes . $field->id . 'allCostID']->data = array(
                            (object)[
                                'name' => 'Koszty nawozów',
                                'y' => $field->getTotalFertilizerOperationsCostProField(),
                                'drilldown' => $field->varietes . $field->id . 'totalFertilizerOperationsCost',
                            ],
                            (object)[
                                'name' => 'Koszty ochrony roślin',
                                'y' => $field->getTotalPlantProtectionOperationsCostProField(),
                                'drilldown' => $field->varietes . $field->id . 'totalPlantProtectionOperationsCost',
                            ],
                            (object)[
                                'name' => 'Inne',
                                'y' => $field->getTotalOtherCostProProField(),
                                'drilldown' => $field->varietes . $field->id . 'totalOtherCosts',
                            ]
                        );
                    }

                    if (count($field->fertilizerOperations) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalFertilizerOperationsCost', $drilldownHashMap)) {
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa * $field->ha),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->id = $field->varietes . $field->id . 'totalFertilizerOperationsCost';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data = array();
                            foreach ($field->fertilizerOperations as $fertilizerOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalFertilizerOperationsCost']->data, (object)[
                                    'name' => $fertilizerOperation->meansName . ' / ' . $fertilizerOperation->date,
                                    'y' => floatval($fertilizerOperation->costProHa * $field->ha),
                                ]);
                            }
                        }
                    }

                    if (count($field->plantProtectionOperations) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalPlantProtectionOperationsCost', $drilldownHashMap)) {
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . $field->id . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa * $field->ha),
                                ]);
                            }
                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->id = $field->varietes . $field->id . 'totalPlantProtectionOperationsCost';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data = array();
                            foreach ($field->plantProtectionOperations as $plantProtectionOperation) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalPlantProtectionOperationsCost']->data, (object)[
                                    'name' => $plantProtectionOperation->meansName . ' / ' . $plantProtectionOperation->date,
                                    'y' => floatval($plantProtectionOperation->costProHa * $field->ha),
                                ]);
                            }
                        }
                    }

                    if (count($field->otherCosts) != 0) {
                        if (key_exists($field->varietes . $field->id . 'totalOtherCosts', $drilldownHashMap)) {
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost),
                                ]);
                            };

                        } else {
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->id = $field->varietes . $field->id . 'totalOtherCosts';
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->name = $field->varietes . ': ' . $field->description;
                            @$drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data = array();
                            foreach ($field->otherCosts as $otherCost) {
                                array_push($drilldownHashMap[$field->varietes . $field->id . 'totalOtherCosts']->data, (object)[
                                    'name' => $otherCost->comment,
                                    'y' => floatval($otherCost->cost),
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


    public
    function getPlant_Varietes_Profit_Details_ProField_ChartData($seasonId, $userId)
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

    public
    function getPlant_Varietes_Profit_Details_ProHa_ChartData($seasonId, $userId)
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


    public
    function getPlant_Varietes_Revenues_Details_ProField_ChartData($seasonId, $userId)
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

    public
    function getPlant_Varietes_Revenues_Details_ProHa_ChartData($seasonId, $userId)
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


    public
    function getPlant_Varietes_FieldSize_Details_ChartData($seasonId, $userId)
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

    public
    function getPlant_Varietes_FieldNumber_Details_ChartData($seasonId, $userId)
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

    public
    function getPlant_Varietes_FieldDescription_Details_ChartData($seasonId, $userId)
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
                    @$seriesHasMap[$field->plant]->drilldown = $field->plant . $field->description;
                }

                if (key_exists($field->plant, $drilldownHashMap)) {
                    array_push($drilldownHashMap[$field->plant]->data, (object)[
                        'name' => $field->description,
                        'y' => $field->ha * 1,
                    ]);
                } else {
                    @$drilldownHashMap[$field->plant]->id = $field->plant . $field->description;
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

    public
    function generateSeasonOverviewTable($seasonId, $userId)
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