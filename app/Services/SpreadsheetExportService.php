<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SpreadsheetExportService
{
    private const HEADER_RGB = '4472C4';

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, mixed>>  $rows  Lignes indexées ; chaque ligne est une suite de valeurs (même ordre que les en-têtes).
     */
    public function fillSheet(Worksheet $sheet, array $headers, array $rows): void
    {
        if ($headers === []) {
            return;
        }

        $sheet->fromArray([$headers], null, 'A1');

        $rowNum = 2;
        foreach ($rows as $row) {
            $values = array_values($row);
            $padded = array_pad($values, count($headers), '');
            $sheet->fromArray([array_slice($padded, 0, count($headers))], null, 'A'.$rowNum);
            $rowNum++;
        }

        $lastRow = max(1, count($rows) + 1);
        $lastColIdx = count($headers);
        $lastColLetter = Coordinate::stringFromColumnIndex($lastColIdx);
        $range = 'A1:'.$lastColLetter.$lastRow;

        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A1:'.$lastColLetter.'1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::HEADER_RGB],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->freezePane('A2');

        for ($c = 1; $c <= $lastColIdx; $c++) {
            $colLetter = Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        if ($lastRow > 1) {
            $sheet->getStyle('A2:'.$lastColLetter.$lastRow)->applyFromArray([
                'font' => ['name' => 'Calibri', 'size' => 11],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
            ]);
        }
    }

    /**
     * Tableau avec bandeau titre / métadonnées, en-têtes, données, bordures, option ligne TOTAUX.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, mixed>>  $totalsRow  Même nombre de colonnes que $headers (texte ou chiffres).
     */
    public function fillStructuredTable(
        Worksheet $sheet,
        string $documentTitle,
        array $metaLines,
        array $headers,
        array $rows,
        ?array $totalsRow = null
    ): void {
        if ($headers === []) {
            return;
        }

        $numCols = count($headers);
        $lastCol = Coordinate::stringFromColumnIndex($numCols);
        $row = 1;

        $sheet->setCellValue('A'.$row, $documentTitle);
        $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
        $sheet->getStyle('A'.$row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'name' => 'Calibri', 'color' => ['rgb' => '1F2937']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(24);
        $row++;

        foreach ($metaLines as $line) {
            $sheet->setCellValue('A'.$row, $line);
            $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
            $sheet->getStyle('A'.$row)->applyFromArray([
                'font' => ['size' => 11, 'name' => 'Calibri', 'color' => ['rgb' => '4B5563']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);
            $row++;
        }

        $row++;
        $headerRow = $row;
        $sheet->fromArray([$headers], null, 'A'.$headerRow);

        $dataRow = $headerRow + 1;
        foreach ($rows as $data) {
            $values = array_values($data);
            $padded = array_pad($values, $numCols, '');
            $sheet->fromArray([array_slice($padded, 0, $numCols)], null, 'A'.$dataRow);
            $dataRow++;
        }

        $lastDataRow = $dataRow - 1;
        if ($lastDataRow < $headerRow) {
            $lastDataRow = $headerRow;
        }

        if ($totalsRow !== null && $totalsRow !== []) {
            $paddedT = array_pad(array_values($totalsRow), $numCols, '');
            $totalLineRow = $lastDataRow + 1;
            $sheet->fromArray([array_slice($paddedT, 0, $numCols)], null, 'A'.$totalLineRow);
            $sheet->getStyle('A'.$totalLineRow.':'.$lastCol.$totalLineRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'name' => 'Calibri', 'color' => ['rgb' => '111827']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E7ECF4'],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '4472C4']],
                ],
            ]);
            $lastDataRow = $totalLineRow;
        }

        $rangeAll = 'A'.$headerRow.':'.$lastCol.$lastDataRow;
        $sheet->getStyle($rangeAll)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A'.$headerRow.':'.$lastCol.$headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::HEADER_RGB],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        if ($lastDataRow > $headerRow) {
            $bodyEnd = $totalsRow !== null && $totalsRow !== [] ? $lastDataRow - 1 : $lastDataRow;
            if ($bodyEnd >= $headerRow + 1) {
                $sheet->getStyle('A'.($headerRow + 1).':'.$lastCol.$bodyEnd)->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                ]);
            }
        }

        $sheet->freezePane('A'.($headerRow + 1));

        for ($c = 1; $c <= $numCols; $c++) {
            $colLetter = Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }

    /**
     * @param  array<int, array{
     *     title: string,
     *     headers: array<int, string>,
     *     rows: array<int, array<int, mixed>>,
     *     document_title?: string,
     *     meta_lines?: array<int, string>,
     *     totals_row?: array<int, mixed>|null
     * }>  $sheets
     */
    public function createMultiSheetSpreadsheet(array $sheets): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $first = true;

        foreach ($sheets as $def) {
            if ($first) {
                $sheet = $spreadsheet->getActiveSheet();
                $first = false;
            } else {
                $sheet = $spreadsheet->createSheet();
            }
            $sheet->setTitle($this->sanitizeSheetTitle($def['title']));
            if (! empty($def['document_title'])) {
                $this->fillStructuredTable(
                    $sheet,
                    (string) $def['document_title'],
                    $def['meta_lines'] ?? [],
                    $def['headers'],
                    $def['rows'],
                    $def['totals_row'] ?? null
                );
            } else {
                $this->fillSheet($sheet, $def['headers'], $def['rows']);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    public function download(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        if (! str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function sanitizeSheetTitle(string $title): string
    {
        $title = preg_replace('/[\\\\\\/\\*\\?\\[\\]:]/u', ' ', $title) ?? $title;
        $title = trim(mb_substr($title, 0, 31));

        return $title !== '' ? $title : 'Feuil1';
    }
}
