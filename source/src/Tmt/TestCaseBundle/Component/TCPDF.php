<?php

namespace Tmt\TestCaseBundle\Component;

class TCPDF extends \FPDI
{
    private $_tplIdx;
    private $template;

    public function __construct($template)
    {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false, false);

        $this->template = $template;

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Test Management Tool');
        $this->SetSubject('Testbericht');
        $this->SetMargins(20, 40, 15);
        $this->setAutoPageBreak(true, 20);
        $this->AddPage();
        $this->SetTextColor(0, 0, 0);
    }

    //Page header
    public function Header()
    {
        if (!empty($this->template)) {
            if (is_null($this->_tplIdx)) {
                $this->setSourceFile($this->template);
                $this->_tplIdx = $this->importPage(1);
            }
            $this->useTemplate($this->_tplIdx);
        }
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Seite '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}