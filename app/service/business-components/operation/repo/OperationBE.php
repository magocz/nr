<?php


class OperationBE
{

    public $id;
    public $fieldId;
    public $seasonId;
    public $date;
    public $meansName;
    public $meansType;
    public $meansDoseInLProHa;
    public $meansDoseInKgProHa;
    public $cause;
    public $economicHarm;
    public $costProHa;
    public $comment;

    /**
     * OperationBE constructor.
     * @param $operation
     */
    public function __construct($operation)
    {
        $this->id = $operation['ID'];
        $this->fieldId = $operation['FIELD_ID'];
        $this->seasonId = $operation['SEASON_ID'];
        $this->date = $operation['DATE'];
        $this->meansName = $operation['MEANS_NAME'];
        $this->meansType = $operation['MEANS_TYPE'];
        $this->meansDoseInLProHa = $operation['MEANS_DOSE_L_HA'];
        $this->meansDoseInKgProHa = $operation['MEANS_DOSE_KG_HA'];
        $this->cause = $operation['CAUSE'];
        $this->economicHarm = $operation['ECONOMIC_HARN'];
        $this->costProHa = $operation['COST_PRO_HA'];
        $this->comment = $operation['COMMENT'];
    }


}