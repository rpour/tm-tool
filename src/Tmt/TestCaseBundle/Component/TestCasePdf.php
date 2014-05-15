<?php

namespace Tmt\TestCaseBundle\Component;

use Tmt\CoreBundle\Component\PDFIcons;

class TestCasePdf extends \TCPDF {
    private $icons;
    private $iconFont;
    private $iconFontFile;
    private $lineheight = 8;
    private $fontSize = 10;
    private $debug = 0;

    public function __construct() {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false, false);

        $this->icons = new PDFIcons();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Test Management Tool');
        $this->SetSubject('Testbericht');
        $this->SetMargins(20, 20, 15);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->setAutoPageBreak(true, 20);
        $this->AddPage();
        $this->SetTextColor(0, 0, 0);
    }

    public function setIconFontFile($file) {
        $this->iconFont = @$this->addTTFfont($file, 'TrueTypeUnicode', '', 32);
    }

    public function _setFontSize($size) {
        $this->fontSize = $size;
    }

    public function _icon($name, $color = null) {
        $border = $this->debug;
        $this->_setColor($color);
        $this->SetFont($this->iconFont, '', null, '', false);
        $this->Cell(10, $this->lineheight, $this->icons->get($name), $border, 0, 'C');
        $this->SetFont('helvetica');
        $this->SetTextColor(0, 0, 0);
    }

    public function _write($txt, $width, $color = null, $align = 'L') {
        $this->_setColor($color);
        $border = $this->debug;
        $this->Cell($width, $this->lineheight, $txt, $border, 0, $align);
        $this->SetTextColor(0, 0, 0);
    }

    public function _setColor($color = null) {
        if (!is_null($color)) {
            $this->SetTextColor(
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2))
            );
        }
    }

    public function drawTestCase($state, $title, $tests) {
        if ($state)
            $this->_icon('icon-check', '4AB471');
        else
            $this->_icon('icon-warning', 'CF5C60');

        $this->_write($title, 145);
        $this->_write($tests, 10, '2A94D6', 'R');
        $this->_icon('icon-list', '2A94D6');
        $this->Ln(8);

        // font #344a5f
        // border          c0dff3
    }
}