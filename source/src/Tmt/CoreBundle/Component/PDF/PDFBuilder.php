<?php

namespace Tmt\CoreBundle\Component\PDF;

class PDFBuilder extends PDF
{
    // public $pdf;

    public function __construct()
    {
        $this->raw = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, false);
        parent::__construct($this->raw);


    }

    public function getLine()
    {
        return new Line($this->raw);
    }

    public function getCell()
    {
        return new Cell($this->raw);
    }

    public function getMultiCell()
    {
        return new MultiCell($this->raw);
    }
}
