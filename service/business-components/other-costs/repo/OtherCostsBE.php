<?php


class OtherCostsBE
{
    public $id;
    public $userId;
    public $fieldId;
    public $seasonId;
    public $initDate;
    public $editDate;
    public $cost;
    public $comment;

    /**
     * OtherCostsBE constructor.
     * @param $otherCosts
     */
    public function __construct($otherCosts)
    {
        $this->id = $otherCosts['ID'];
        $this->userId = $otherCosts['USER_ID'];
        $this->fieldId = $otherCosts['FIELD_ID'];
        $this->seasonId = $otherCosts['SEASON_ID'];
        $this->initDate = $otherCosts['INIT_DATE'];
        $this->editDate = $otherCosts['EDIT_DATE'];
        $this->cost = $otherCosts['COST'];
        $this->comment = $otherCosts['COMMENT'];
    }

}