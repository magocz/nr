<?php

class FieldExcelGenerator
{

    public static function generateFieldExcel($field)
    {

        $objPHPExcel = new PHPExcel();


        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Neldam Software Solutions - Excel Generator")
            ->setLastModifiedBy("Neldam Software Solutions - Excel Generator")
            ->setTitle($field->fieldNumber . ' ')
            ->setCategory("");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle('Ogólne dane');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(60);

        self::addOverviewFieldInfo($field, $objPHPExcel);
        self::addOperationsInfo(array_merge($field->fertilizerOperations, $field->plantProtectionOperations), $objPHPExcel);

        $objPHPExcel->getActiveSheet()
            ->getStyle('H3:P3')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objPHPExcel->getActiveSheet()
            ->getStyle('A3:B9')
            ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()
            ->getStyle('D3:E9')
            ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    private static function addOperationsInfo($operations, $objPHPExcel)
    {
        $index = 3;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $index, 'Data zabiegu');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $index, 'Zastosowany środek');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $index, 'Typ środka');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $index, 'Dawka w l na hektar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $index, 'Dawka w kg na hektar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $index, 'Powód zabiegu');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $index, 'Szkodliwość ekonomiczna');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $index, 'Koszt na hektar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15, $index, 'Uwagi');
        $index++;
        foreach ($operations as $operation) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $index, $operation->date);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $index, $operation->meansName);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $index, $operation->meansType == 'plantProtection' ? 'ochrona roślin' : 'nawóz');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $index, $operation->meansDoseInLProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $index, $operation->meansDoseInKgProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $index, $operation->cause);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $index, $operation->economicHarm);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $index, $operation->costProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15, $index, $operation->comment);
            $index++;
        }
    }

    /**
     * @param $field
     * @param $objPHPExcel
     */
    private static function addOverviewFieldInfo($field, $objPHPExcel)
    {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, 'Numer działki: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 1, $field->fieldNumber);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 3, 'Opis pola: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 3, $field->description);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 3, 'Całkowity koszt: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 3, $field->getTotalCostProField());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 4, 'Uprawiana roślina: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 4, $field->plant);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 4, 'Całkowity koszt na hektar: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 4, $field->getTotalCostProHa());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 5, 'Odmiana: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 5, $field->varietes);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 5, 'Koszt nawozów: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 5, $field->getTotalFertilizerOperationsCostProField());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 6, 'Powierzchnia pola: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 6, $field->ha);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 6, 'Koszt nawozów na hektar: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 6, $field->getTotalFertilizerOperationsCostProHa());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 7, 'Przeprowadzone operacje: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 7, $field->operationsNumber);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 7, 'Koszt ochrony roślin: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 7, $field->getTotalPlantProtectionOperationsCostProField());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 8, 'Cena za tonę: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 8, $field->plantPrice);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 8, 'Koszt ochrony roślin na hektar: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 8, $field->getTotalPlantProtectionOperationsCostProHa());

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 9, 'Ton na hektar: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 9, $field->tonsProHa);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 9, 'Dodatkowe koszty: ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 9, $field->getTotalOtherCostProProField());
    }

}

