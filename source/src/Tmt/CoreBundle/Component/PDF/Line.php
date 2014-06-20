<?php

namespace Tmt\CoreBundle\Component\PDF;

/**
 * http://www.tcpdf.org/doc/code/classTCPDF.html#a89c0fac95da962719ba92b057df9c201
 */
class Line extends PDF
{

    protected $x1; // (float) Abscissa of first point.
    protected $y1; // (float) Ordinate of first point.
    protected $x2; // (float) Abscissa of second point.
    protected $y2; // (float) Ordinate of second point.

    /**
     * (array) Line style.
     * Array like for SetLineStyle().
     * Default value: default line style (empty array).
     */
    protected $style;

    public function setX1($x1)
    {
        $this->x1 = $x1;
        return $this;
    }

    public function setY1($y1)
    {
        $this->y1 = $y1;
        return $this;
    }

    public function setX2($x2)
    {
        $this->x2 = $x2;
        return $this;
    }

    public function setY2($y2)
    {
        $this->y2 = $y2;
        return $this;
    }

    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }


    public function __construct($pdf)
    {
        parent::__construct($pdf);
        $this->clear();
    }


    public function draw()
    {
        $this->raw->Line($this->x1, $this->y1, $this->x2, $this->y2, $this->style);
        return $this;
    }

    public function clear()
    {
        parent::clear();

        $this->x1 = 20;
        $this->y1 = $this->raw->getY();
        $this->x2 = 195;
        $this->y2 = $this->raw->getY();
        $this->style = array();

        return $this;
    }
}
