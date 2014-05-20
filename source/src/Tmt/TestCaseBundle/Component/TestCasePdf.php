<?php

namespace Tmt\TestCaseBundle\Component;

use Tmt\CoreBundle\Component\PDFIcons;
use Tmt\CoreBundle\Component\PDF\PDFBuilder;

class TestCasePdf extends \TCPDF {
    private $pdf;
    private $filename;
    private $iconFontPath;
    private $backgroundRow = 0;

    public function __construct($projectName, $iconFontPath) {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false, false);

        $this->pdf = new PDFBuilder();
        $this->pdf->raw->SetCreator(PDF_CREATOR);
        $this->pdf->raw->SetAuthor('Test Management Tool');
        $this->pdf->raw->SetSubject('Testbericht');
        $this->pdf->raw->SetMargins(20, 20, 15);
        $this->pdf->raw->setPrintHeader(false);
        $this->pdf->raw->setPrintFooter(false);
        $this->pdf->raw->setAutoPageBreak(true, 20);
        $this->pdf->raw->AddPage();
        $this->pdf->raw->SetTextColor(0, 0, 0);
        $this->iconFontPath = $iconFontPath;

        $this->filename = preg_replace('/\W/', '', strtolower($projectName)) . date('_Y-m-d') . '.pdf';
    }

    public function h1($headline) {
        $cell = $this->pdf->getCell();
        $cell
            ->ln()
            ->setFontSize(20)
            ->setText($headline)
            ->draw()->ln();
    }

    public function h2($headline) {
        $cell = $this->pdf->getCell();
        $cell
            ->ln()
            ->setFontSize(16)
            ->setText($headline)
            ->draw()->ln();
    }

    public function drawTestcase($state, $title, $testCount) {
        $backgroundColor = 'FAFFF9';

        if (($this->backgroundRow = !$this->backgroundRow))
            $backgroundColor = 'F2F7F2';

        $cell = $this->pdf->getCell();
        $cell
            ->setBackgroundColor($backgroundColor)
            ->setFontFile($this->iconFontPath)
            ->setFontSize(12)
            ->setWidth(8)
            ->setAlign('C');

        if ($state)
            $cell->setColor('4AB471')->setText('::icon::icon-check');
        else
            $cell->setColor('CF5C60')->setText('::icon::icon-warning');

        $cell
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(150)
                ->setText($title)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setAlign('R')
                ->setColor('2A94D6')
                ->setWidth(10)
                ->setText($testCount)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setAlign('C')
                ->setColor('2A94D6')
                ->setText('::icon::icon-list')
            ->draw()->ln();
    }

    public function drawTest($state, $date, $user, $version) {
        $backgroundColor = 'FAFFF9';

        if (($this->backgroundRow = !$this->backgroundRow))
            $backgroundColor = 'F2F7F2';

        $cell = $this->pdf->getCell();
        $cell
            ->setBackgroundColor($backgroundColor)
            ->setFontFile($this->iconFontPath)
            ->setFontSize(10)
            ->setWidth(8)
            ->setAlign('C');

        if ($state)
            $cell->setColor('4AB471')->setText('::icon::icon-check');
        else
            $cell->setColor('CF5C60')->setText('::icon::icon-warning');

        $cell
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(70)
                ->setText($date)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(70)
                ->setText($user)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setAlign('R')
                ->setText($version)
            ->draw()->clear()
        ->ln();
    }

    public function download() {
        $this->pdf->raw->Output($this->filename, 'D');
    }
}