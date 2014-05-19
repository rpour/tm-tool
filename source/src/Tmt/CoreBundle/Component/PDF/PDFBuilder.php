<?php

namespace Tmt\CoreBundle\Component\PDF;

class PDFBuilder extends PDF {
    public $pdf;

    public function __construct($author = '', $subject = '') {
        $this->raw = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, false);
        parent::__construct($this->raw);

        $this->raw->SetCreator(PDF_CREATOR);
        $this->raw->SetAuthor($author);
        $this->raw->SetSubject($subject);
        $this->raw->SetMargins(20, 20, 15);
        $this->raw->setPrintHeader(false);
        $this->raw->setPrintFooter(false);
        $this->raw->setAutoPageBreak(true, 20);
        $this->raw->SetTextColor(0, 0, 0);
        $this->raw->SetFont($this->fontFamily);

        $this->raw->AddPage();
    }

    public function getTable() {return new Table($this->raw);}
    public function getCell()  {return new Cell($this->raw); }
}