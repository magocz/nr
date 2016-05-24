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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

        self::addOverviewFieldInfo($field, $objPHPExcel);
        $index = self::addOperationsInfo(array_merge($field->fertilizerOperations, $field->plantProtectionOperations), $objPHPExcel);

        $objPHPExcel->getActiveSheet()
            ->getStyle('A1:J1')
            ->getFont()->setSize(16);

        $objPHPExcel->getActiveSheet()->getStyle('A3:W999')
            ->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet()
            ->getStyle('A4:J4')
            ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);


        $objPHPExcel->getActiveSheet()
            ->getStyle('A4:J4')
            ->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()
            ->getStyle('A5:J' . $index)
            ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()
            ->getStyle('A5:J' . $index)
            ->getFont()->setSize(8);

        $objPHPExcel->getActiveSheet()->getStyle('A4:J' . $index)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:J' . $index)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    private static function addOperationsInfo($operations, $objPHPExcel)
    {
        $index = 4;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $index, 'lp.');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $index, 'Data zabiegu');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $index, 'Zastosowany środek');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $index, 'Typ środka');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $index, 'Dawka w l/ha');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $index, 'Dawka w kg/ha');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $index, 'Powód zabiegu');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $index, 'Szkodliwość ekonomiczna');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $index, 'Koszt na hektar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $index, 'Uwagi');
        $index++;
        foreach ($operations as $operation) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $index, $index - 4);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $index, $operation->date);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $index, $operation->meansName);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $index, $operation->meansType == 'plantProtection' ? 'ochrona roślin' : 'nawóz');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $index, $operation->meansDoseInLProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $index, $operation->meansDoseInKgProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $index, $operation->cause);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $index, $operation->economicHarm);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $index, $operation->costProHa);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $index, $operation->comment);
            $index++;
        }
        $index--;
        return $index;
    }

    /**
     * @param $field
     * @param $objPHPExcel
     */
    private static function addOverviewFieldInfo($field, $objPHPExcel)
    {
        $fieldOverview = 'Numer działki: ' . $field->fieldNumber;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, $fieldOverview);
        $fieldOverview = 'Opis: ' . $field->description . ' / ' . 'powierzchnia ' . $field->ha . ' ha';
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 2, $fieldOverview);

    }

}

