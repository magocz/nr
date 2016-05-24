<?php

class FieldBA
{
    public function updateField($fieldData)
    {
        return FieldRepo::updateFieldById($fieldData, $_SESSION['id']);
    }

    public function deleteField($fieldId)
    {
        return FieldRepo::deleteFieldById($fieldId, $_SESSION['id']);
    }

    public function saveField($fieldId)
    {
        return FieldRepo::saveNewField($fieldId, $_SESSION['id']);
    }

    public function generateFieldData($fieldId)
    {
        return json_encode(FieldRepo::findFieldById($fieldId, $_SESSION['id']));
    }

    public function generateFieldExcel($fieldId)
    {
        $field = FieldRepo::findFieldById($fieldId, $_SESSION['id']);
        if ($field == null) {
            return new PHPExcel();
        }
        return FieldExcelGenerator::generateFieldExcel($field);
    }

    public function generateFieldName($fieldId)
    {
        return FieldRepo::getFieldName($fieldId, $_SESSION['id']);
    }
}