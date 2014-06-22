<?php

namespace Tmt\CoreBundle\Component\PDF;

/**
 * http://www.tcpdf.org/doc/code/classTCPDF.html#aa81d4b585de305c054760ec983ed3ece
 */
class MultiCell extends Cell
{
    protected $x;
    protected $y;
    protected $reseth;
    protected $ishtml;
    protected $autopadding;
    protected $maxh;
    protected $fitcell;

    public function __construct($pdf)
    {
        parent::__construct($pdf);
        $this->clear();
    }

    public function draw()
    {
        $this->raw->MultiCell(
            $this->width,
            $this->height,
            $this->text,
            $this->border,
            $this->align,
            $this->fill,
            $this->ln,
            $this->x,
            $this->y,
            $this->reseth,
            $this->stretch,
            $this->ishtml,
            $this->autopadding,
            $this->maxh,
            $this->valign,
            $this->fitcell
        );

        return $this;
    }

    public function clear()
    {
        parent::clear();

        $this->x = '';
        $this->y = '';
        $this->reseth = true;
        $this->ishtml = false;
        $this->autopadding = true;
        $this->maxh = 0;
        $this->fitcell = false;

        return $this;
    }
}
