<?php

namespace Tmt\TestCaseBundle\Component;

use Tmt\CoreBundle\Component\PDFIcons;
use Tmt\CoreBundle\Component\PDF\PDFBuilder;
use Tmt\TestCaseBundle\Component\TCPDF;

class TestCasePdf
{
    private $pdf;
    private $filename;
    private $iconFontPath;
    private $backgroundRow = 1;

    public function __construct($projectName, $iconFontPath, $sourceFile = null)
    {
        $this->pdf = new PDFBuilder(
            new TCPDF($sourceFile)
        );
        $this->iconFontPath = $iconFontPath;
        $this->filename = preg_replace('/\W/', '', strtolower($projectName)) . date('_Y-m-d') . '.pdf';
    }



    public function newPage()
    {
        $this->pdf->raw->AddPage();
        return $this;
    }

    public function title($title)
    {
        $cell = $this->pdf->getCell();
        $cell
            ->newLine()
            ->setFontSize(20)
            ->setText($title)
            ->setAlign('C')
            ->draw()->newLine();
    }


    public function header1($headline)
    {
        $cell = $this->pdf->getCell();
        $cell
            ->newLine()
            ->setFontSize(18)
            ->setText($headline)
            ->draw()->newLine();
    }

    public function header2($headline)
    {
        $cell = $this->pdf->getCell();
        $cell
            ->newLine()
            ->setFontSize(16)
            ->setText($headline)
            ->draw()->newLine();
    }

    public function seperator()
    {
        $line = $this->pdf->getLine();
        $line->draw();
    }

    public function resetBackground()
    {
        $this->backgroundRow = 1;
        return $this;
    }

    public function drawTestcase($state, $title, $testCount)
    {
        $backgroundColor = 'FFFFFF';

        if (($this->backgroundRow = !$this->backgroundRow)) {
            $backgroundColor = 'EFEFEF';
        }

        $cell = $this->pdf->getCell();
        $cell
            ->setBackgroundColor($backgroundColor)
            ->setFontFile($this->iconFontPath)
            ->setFontSize(12)
            ->setWidth(8)
            ->setAlign('C');

        $color = 'CF5C60';
        $text  = '::icon::icon-warning';

        if ($state) {
            $color = '4AB471';
            $text  = '::icon::icon-check';
        }

        $cell
            ->setBackgroundColor($backgroundColor)
            ->setColor($color)
            ->setText($text);

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
            ->draw()->newLine();
    }

    public function drawTest($state, $date, $user, $os, $browser, $version, $header = false)
    {
        $backgroundColor = 'FFFFFF';

        if (($this->backgroundRow = !$this->backgroundRow)) {
            $backgroundColor = 'EFEFEF';
        }

        $cell = $this->pdf->getCell();

        $cell
            ->setBackgroundColor($backgroundColor)
            ->setFontFile($this->iconFontPath)
            ->setFontSize(10)
            ->setWidth(8)
            ->setAlign('C');

        $color = 'CF5C60';
        $text  = '::icon::icon-warning';

        if ($state) {
            $color = '4AB471';
            $text  = '::icon::icon-check';
        }

        // header
        if ($header === true) {
            $cell->raw->SetFont('', 'B');
            $backgroundColor = 'CCCCCC';
            $text = "";
        }

        $cell
            ->setBackgroundColor($backgroundColor)
            ->setColor($color)
            ->setText($text);


        $cell
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(30)
                ->setText($date)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(40)
                ->setText($user)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(40)
                ->setText($os)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setWidth(40)
                ->setText($browser)
            ->draw()->clear()
                ->setBackgroundColor($backgroundColor)
                ->setAlign('R')
                ->setText($version)
            ->draw()->clear()
        ->newLine();
    }

    public function draw($testcase)
    {
        $multicell = $this->pdf->getMultiCell();


        $cell = $this->pdf->getCell();


        $this->header2(
            '#' . $testcase->getId() . ' ' .
            $testcase->getTitle()
        );

        $multicell->raw->SetFont('', 'B');
        $multicell
            ->setFontSize(8)
                ->setText($testcase->getDescription())
                ->draw()->clear()->newLine();

        $cell->setText('')->draw()->clear()->newLine();

        $counter = 0;
        foreach (json_decode($testcase->getData()) as $data) {
            $cell->raw->SetFont('', 'B');
            $cell
                ->setText('Testschritt #' . ++$counter)
                ->draw()->clear()->newLine();

            $multicell
                ->setFontSize(8)
                ->setText(trim($data[0]))
                ->draw()->newLine()->clear();
            $cell->setText('')->draw()->clear()->newLine();

            $cell->raw->SetFont('', 'B');
            $cell
                ->setText('Erwartetes Ergebnis')
                ->draw()->newLine()->clear();

            $multicell
                ->setFontSize(8)
                ->setText(trim($data[1]))
                ->draw()->newLine()->clear();
            $cell->setText('')->draw()->clear()->newLine();
        }
    }

    public function download()
    {
        $this->pdf->raw->Output($this->filename, 'D');
    }
}
