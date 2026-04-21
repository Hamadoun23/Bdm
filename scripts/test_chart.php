<?php

require __DIR__.'/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet;
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data');
$sheet->fromArray([['Cat', 'Val']], null, 'A1');
$sheet->fromArray([['A', 10], ['B', 20], ['C', 15]], null, 'A2');

$labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, ['Serie1'])];
$categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 3, ['A', 'B', 'C'])];
$values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, null, null, 3, [10, 20, 15])];

$series = new DataSeries(DataSeries::TYPE_DOUGHNUTCHART, null, [0], $labels, $categories, $values);
$plot = new PlotArea(null, [$series]);
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$chart = new Chart('chart1', new Title('Test'), $legend, $plot);
$chart->setTopLeftPosition('E2');
$chart->setBottomRightPosition('M20');
$sheet->addChart($chart);

$path = __DIR__.'/../storage/app/test-chart.xlsx';
$w = new Xlsx($spreadsheet);
$w->setIncludeCharts(true);
$w->save($path);
echo "saved $path\n";
