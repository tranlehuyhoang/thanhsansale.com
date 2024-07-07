<?php
namespace App\Services\Common;

class ExcelHelper
{
    public static function exportToExcelWithTemplate($data, $templatePath, $outputPath, $startRow = 3)
    {
        // Load the template Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();
        // Map data properties to row 2 (starting from column A)
        $col = 'A';
        foreach ($data[0] as $property => $value) {
            $sheet->setCellValue($col . '2', $property);
            $col++;
        }

        // Insert data into the spreadsheet starting from row 3
        foreach ($data as $row) {
            $col = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($col . $startRow, $value);
                $col++;
            }
            $startRow++;
        }

        // Check if $col and $startRow are set and valid
        if (isset($col, $startRow) && $startRow > 1 && !empty($col)) {
            // Set border style
            $sheet->getStyle('A1:' . $col . ($startRow - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
        // Save the spreadsheet to the specified output path
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputPath);
        return $outputPath;
    }
    public static function exportToExcelNoProperty($data, $templatePath, $outputPath, $startRow = 3)
    {
        // Load the template Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Insert data into the spreadsheet starting from specified start row
        foreach ($data as $row) {
            $col = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($col . $startRow, $value);
                $col++;
            }
            $startRow++;
        }

        // Set border style if there are valid data
        if (isset($col, $startRow) && $startRow > 3 && !empty($col)) {
            $endCol = chr(ord($col) - 1); // Adjust column to last filled column
            $sheet->getStyle('A3:' . $endCol . ($startRow - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Save the spreadsheet to the specified output path
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputPath);

        return $outputPath;
    }

}