<?php

namespace App\Services;

use App\Models\TypeCarte;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Export Excel / Word avec graphiques Office modifiables (objets natifs, pas d’images PNG).
 */
class GraphiquesDashboardExportService
{
    private const TOP_N_COMM = 5;

    private const TOP_N_AG = 10;

    public function __construct(
        private SpreadsheetExportService $spreadsheetExportService
    ) {}

    /**
     * @param  array<string, mixed>  $synthese  Retour de {@see CampagneRapportService::synthese}.
     */
    public function downloadSyntheseCampagneExcel(
        string $campagneNom,
        Carbon $dateDebut,
        Carbon $dateFin,
        array $synthese,
        string $fileBase
    ): StreamedResponse {
        $spreadsheet = $this->buildSyntheseCampagneSpreadsheet($campagneNom, $dateDebut, $dateFin, $synthese);

        return $this->spreadsheetExportService->download($spreadsheet, $fileBase.'_graphiques.xlsx');
    }

    /**
     * @param  array<string, mixed>  $synthese
     */
    public function downloadSyntheseCampagneWord(
        string $campagneNom,
        Carbon $dateDebut,
        Carbon $dateFin,
        array $synthese,
        string $fileBase
    ): StreamedResponse {
        [$parType, $commChart, $agChart] = $this->extractSyntheseChartData($synthese);

        $phpWord = new PhpWord;
        $phpWord->getSettings()->setThemeFontLang(new Language('fr-FR'));
        $section = $phpWord->addSection();
        $section->addText(
            'Synthèse — '.$campagneNom.' ('.$dateDebut->format('d/m/Y').' – '.$dateFin->format('d/m/Y').')',
            ['bold' => true, 'size' => 16]
        );
        $section->addTextBreak(1);

        $this->addWordChart($section, 'Mix des ventes par type de carte', 'doughnut', $parType['labels'], $parType['values']);
        $section->addTextBreak(2);
        $this->addWordChart($section, 'Top vendeurs — part du total (%)', 'bar', $commChart['labels'], $commChart['values']);
        $section->addTextBreak(2);
        $this->addWordChart($section, 'Part des agences (ventes)', 'pie', $agChart['labels'], $agChart['values']);

        return new StreamedResponse(function () use ($phpWord): void {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="'.$fileBase.'_graphiques.docx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @param  array{total_ventes: int, par_type: Collection}  $stats
     * @param  Collection<int, array{label: string, ventes: int}>  $topCommerciaux
     * @param  Collection<int, array{label: string, ventes: int}>  $ventesParAgence
     * @param  Collection<int, TypeCarte>  $typesCartes
     */
    public function downloadPerformancesExcel(
        string $titrePeriode,
        array $stats,
        Collection $topCommerciaux,
        Collection $ventesParAgence,
        Collection $typesCartes,
        string $fileBase
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet;
        $info = $spreadsheet->getActiveSheet();
        $info->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Infos'));
        $info->setCellValue('A1', 'Performances — '.$titrePeriode);
        $info->setCellValue('A2', 'Total ventes : '.(int) ($stats['total_ventes'] ?? 0));

        $sheet1 = $spreadsheet->createSheet();
        $sheet1->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Top commerciaux'));
        $labelsTop = $topCommerciaux->pluck('label')->all();
        $valsTop = $topCommerciaux->pluck('ventes')->map(fn ($v) => (int) $v)->all();
        $this->fillTwoColumnData($sheet1, 'Commercial', 'Ventes', $labelsTop, $valsTop);
        $this->addBarChartHorizontal($sheet1, 'Top commerciaux', 'Ventes', 'E2', 'P28');

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Agences'));
        $labelsAg = $ventesParAgence->pluck('label')->all();
        $valsAg = $ventesParAgence->pluck('ventes')->map(fn ($v) => (int) $v)->all();
        $this->fillTwoColumnData($sheet2, 'Agence', 'Ventes', $labelsAg, $valsAg);
        $this->addPieOrDoughnutChart($sheet2, DataSeries::TYPE_DOUGHNUTCHART, 'Répartition agences', 'Ventes', 'E2', 'P28');

        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Types carte'));
        $parType = collect($stats['par_type'] ?? []);
        $labelsT = [];
        $valsT = [];
        foreach ($typesCartes as $tc) {
            $labelsT[] = $tc->code;
            $valsT[] = (int) ($parType->get($tc->id) ?? 0);
        }
        $this->fillTwoColumnData($sheet3, 'Type', 'Ventes', $labelsT, $valsT);
        $this->addColumnChart($sheet3, 'Ventes par type', 'Ventes', 'E2', 'P28');

        $spreadsheet->setActiveSheetIndex(0);

        return $this->spreadsheetExportService->download($spreadsheet, $fileBase.'_graphiques.xlsx');
    }

    /**
     * @param  array{total_ventes: int, par_type: Collection}  $stats
     * @param  Collection<int, array{label: string, ventes: int}>  $topCommerciaux
     * @param  Collection<int, array{label: string, ventes: int}>  $ventesParAgence
     * @param  Collection<int, TypeCarte>  $typesCartes
     */
    public function downloadPerformancesWord(
        string $titrePeriode,
        array $stats,
        Collection $topCommerciaux,
        Collection $ventesParAgence,
        Collection $typesCartes,
        string $fileBase
    ): StreamedResponse {
        $labelsTop = $topCommerciaux->pluck('label')->all();
        $valsTop = $topCommerciaux->pluck('ventes')->map(fn ($v) => (int) $v)->all();
        $labelsAg = $ventesParAgence->pluck('label')->all();
        $valsAg = $ventesParAgence->pluck('ventes')->map(fn ($v) => (int) $v)->all();
        $parType = collect($stats['par_type'] ?? []);
        $labelsT = [];
        $valsT = [];
        foreach ($typesCartes as $tc) {
            $labelsT[] = $tc->code;
            $valsT[] = (int) ($parType->get($tc->id) ?? 0);
        }

        $phpWord = new PhpWord;
        $phpWord->getSettings()->setThemeFontLang(new Language('fr-FR'));
        $section = $phpWord->addSection();
        $section->addText('Performances — '.$titrePeriode, ['bold' => true, 'size' => 16]);
        $section->addText('Total ventes : '.(int) ($stats['total_ventes'] ?? 0));
        $section->addTextBreak(1);
        $this->addWordChart($section, 'Top commerciaux', 'bar', $labelsTop, $valsTop);
        $section->addTextBreak(2);
        $this->addWordChart($section, 'Répartition agences', 'doughnut', $labelsAg, $valsAg);
        $section->addTextBreak(2);
        $this->addWordChart($section, 'Ventes par type de carte', 'column', $labelsT, $valsT);

        return new StreamedResponse(function () use ($phpWord): void {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="'.$fileBase.'_graphiques.docx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @param  array<string, mixed>  $synthese
     */
    private function buildSyntheseCampagneSpreadsheet(
        string $campagneNom,
        Carbon $dateDebut,
        Carbon $dateFin,
        array $synthese
    ): Spreadsheet {
        [$parType, $commChart, $agChart] = $this->extractSyntheseChartData($synthese);

        $spreadsheet = new Spreadsheet;
        $info = $spreadsheet->getActiveSheet();
        $info->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Resume'));
        $info->setCellValue('A1', 'Synthèse — '.$campagneNom);
        $info->setCellValue('A2', $dateDebut->format('d/m/Y').' – '.$dateFin->format('d/m/Y'));
        $info->setCellValue('A3', 'Total ventes : '.(int) ($synthese['resume']['total_ventes'] ?? 0));

        $sheet1 = $spreadsheet->createSheet();
        $sheet1->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Types'));
        $this->fillTwoColumnData($sheet1, 'Type', 'Ventes', $parType['labels'], $parType['values']);
        $this->addPieOrDoughnutChart($sheet1, DataSeries::TYPE_DOUGHNUTCHART, 'Mix types', 'Ventes', 'E2', 'P30');

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Commerciaux'));
        $this->fillTwoColumnData($sheet2, 'Commercial', 'Part %', $commChart['labels'], $commChart['values']);
        $this->addBarChartHorizontal($sheet2, 'Top vendeurs', 'Part %', 'E2', 'P32');

        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Agences'));
        $this->fillTwoColumnData($sheet3, 'Agence', 'Ventes', $agChart['labels'], $agChart['values']);
        $this->addPieOrDoughnutChart($sheet3, DataSeries::TYPE_PIECHART, 'Agences', 'Ventes', 'E2', 'P30');

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * @param  array<string, mixed>  $synthese
     * @return array{0: array{labels: string[], values: float[]|int[]}, 1: array{labels: string[], values: float[]}, 2: array{labels: string[], values: int[]}}
     */
    private function extractSyntheseChartData(array $synthese): array
    {
        $parTypeRows = collect($synthese['par_type_carte'] ?? []);
        $labelsType = $parTypeRows->pluck('code')->map(fn ($c) => (string) $c)->all();
        $valsType = $parTypeRows->pluck('total_ventes')->map(fn ($v) => (int) $v)->all();

        $totalCampagne = (int) ($synthese['resume']['total_ventes'] ?? 0);
        $denom = $totalCampagne > 0 ? $totalCampagne : 1;
        $commAvecVentes = collect($synthese['commerciaux'] ?? [])->filter(fn ($l) => $l['total_ventes'] > 0)->sortByDesc('total_ventes')->values();
        $top = $commAvecVentes->take(self::TOP_N_COMM);
        $labelsComm = [];
        $valsComm = [];
        foreach ($top as $l) {
            $labelsComm[] = $l['user_name'];
            $valsComm[] = round(100 * (int) $l['total_ventes'] / $denom, 2);
        }
        $tail = $commAvecVentes->slice(self::TOP_N_COMM);
        if ($tail->isNotEmpty()) {
            $labelsComm[] = 'Autres commerciaux ('.$tail->count().')';
            $vAutres = (int) $tail->sum('total_ventes');
            $valsComm[] = round(100 * $vAutres / $denom, 2);
        }

        $agAvec = collect($synthese['agences'] ?? [])->filter(fn ($l) => $l['total_ventes'] > 0)->sortByDesc('total_ventes')->values();
        $topAg = $agAvec->take(self::TOP_N_AG);
        $labelsAg = [];
        $valsAg = [];
        foreach ($topAg as $l) {
            $labelsAg[] = $l['agence_nom'];
            $valsAg[] = (int) $l['total_ventes'];
        }
        $tailAg = $agAvec->slice(self::TOP_N_AG);
        if ($tailAg->isNotEmpty()) {
            $labelsAg[] = 'Autres agences ('.$tailAg->count().')';
            $valsAg[] = (int) $tailAg->sum('total_ventes');
        }

        return [
            ['labels' => $labelsType, 'values' => $valsType],
            ['labels' => $labelsComm, 'values' => $valsComm],
            ['labels' => $labelsAg, 'values' => $valsAg],
        ];
    }

    /**
     * @param  array<int|string|float>  $values
     */
    private function fillTwoColumnData(Worksheet $sheet, string $h1, string $h2, array $labels, array $values): void
    {
        $sheet->fromArray([[$h1, $h2]], null, 'A1');
        $row = 2;
        foreach ($labels as $i => $label) {
            $sheet->setCellValue('A'.$row, $label);
            $sheet->setCellValue('B'.$row, $values[$i] ?? 0);
            $row++;
        }
    }

    private function quotedSheetTitle(Worksheet $sheet): string
    {
        $title = $sheet->getTitle();

        return str_replace("'", "''", $title);
    }

    /**
     * @return array{0: string, 1: string, 2: int}|null
     */
    private function categoryValueRanges(Worksheet $sheet): ?array
    {
        $lastRow = (int) $sheet->getHighestDataRow('A');
        if ($lastRow < 2) {
            return null;
        }
        $n = $lastRow - 1;
        $q = $this->quotedSheetTitle($sheet);
        $catRange = "'{$q}'!\$A\$2:\$A\${$lastRow}";
        $valRange = "'{$q}'!\$B\$2:\$B\${$lastRow}";

        return [$catRange, $valRange, $n];
    }

    private function addPieOrDoughnutChart(
        Worksheet $sheet,
        string $plotType,
        string $chartTitle,
        string $seriesLabel,
        string $chartTopLeft,
        string $chartBottomRight
    ): void {
        $ranges = $this->categoryValueRanges($sheet);
        if ($ranges === null) {
            return;
        }
        [$catRange, $valRange, $n] = $ranges;

        $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, [$seriesLabel])];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $catRange, null, $n)];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valRange, null, $n)];

        $series = new DataSeries(
            $plotType,
            null,
            [0],
            $labels,
            $categories,
            $values
        );

        $plot = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
        $chart = new Chart('chart_'.$sheet->getTitle(), new Title($chartTitle), $legend, $plot);
        $chart->setTopLeftPosition($chartTopLeft);
        $chart->setBottomRightPosition($chartBottomRight);
        $sheet->addChart($chart);
    }

    private function addBarChartHorizontal(
        Worksheet $sheet,
        string $chartTitle,
        string $seriesLabel,
        string $chartTopLeft,
        string $chartBottomRight
    ): void {
        $ranges = $this->categoryValueRanges($sheet);
        if ($ranges === null) {
            return;
        }
        [$catRange, $valRange, $n] = $ranges;

        $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, [$seriesLabel])];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $catRange, null, $n)];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valRange, null, $n)];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            [0],
            $labels,
            $categories,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_BAR);

        $plot = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
        $chart = new Chart('chart_'.$sheet->getTitle(), new Title($chartTitle), $legend, $plot);
        $chart->setTopLeftPosition($chartTopLeft);
        $chart->setBottomRightPosition($chartBottomRight);
        $sheet->addChart($chart);
    }

    private function addColumnChart(
        Worksheet $sheet,
        string $chartTitle,
        string $seriesLabel,
        string $chartTopLeft,
        string $chartBottomRight
    ): void {
        $ranges = $this->categoryValueRanges($sheet);
        if ($ranges === null) {
            return;
        }
        [$catRange, $valRange, $n] = $ranges;

        $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, [$seriesLabel])];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $catRange, null, $n)];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valRange, null, $n)];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            [0],
            $labels,
            $categories,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plot = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
        $chart = new Chart('chart_'.$sheet->getTitle(), new Title($chartTitle), $legend, $plot);
        $chart->setTopLeftPosition($chartTopLeft);
        $chart->setBottomRightPosition($chartBottomRight);
        $sheet->addChart($chart);
    }

    /**
     * @param  array<int|string|float>  $values
     */
    private function addWordChart(Section $section, string $title, string $type, array $labels, array $values): void
    {
        $section->addText($title, ['bold' => true, 'size' => 12]);
        $style = [
            'width' => Converter::cmToEmu(16),
            'height' => Converter::cmToEmu(10),
            'title' => $title,
            'showLegend' => true,
        ];
        $section->addChart($type, $labels, $values, $style);
    }
}
