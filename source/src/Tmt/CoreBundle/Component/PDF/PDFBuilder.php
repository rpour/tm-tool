<?php

namespace Tmt\CoreBundle\Component\PDF;

class PDFBuilder extends PDF
{
    public function __construct($pdf)
    {
        $this->raw = $pdf;
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
