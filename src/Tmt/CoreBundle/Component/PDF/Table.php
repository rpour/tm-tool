<?php

namespace Tmt\CoreBundle\Component\PDF;

/**
 * http://www.tcpdf.org/doc/code/classTCPDF.html#a33b265e5eb3e4d1d4fedfe29f8166f31
 */
class Table extends Cell {
    protected $rowSize = array();
    protected $rows = array();
    protected $rowAlign = array();
    protected $rowBackground = array();

    public function __construct($pdf) {
        parent::__construct($pdf);
        $this->clear();
    }

    public function setRow() {
        $this->rows[] = func_get_args();
        return $this;
    }

    public function setRowSize() {
        $this->rowSize = func_get_args();
        return $this;
    }

    public function setRowAlign() {
        $this->rowAlign = func_get_args();
        return $this;
    }

    public function toggleBackground($rowBackground) {
        $this->rowBackground = $rowBackground;
        return $this;
    }

    public function draw() {
        $toggle = 1;

        foreach ($this->rows as $row) {
            $background = 'ffffff';

            if (count($this->rowBackground) === 2 && $toggle) {
                $background = $this->rowBackground[0];
            } else if (count($this->rowBackground) === 2 && !$toggle) {
                $background = $this->rowBackground[1];
            } else if (count($this->rowBackground) === 1 && $toggle) {
                $background = $this->rowBackground[0];
            }
            $toggle = !$toggle;

            for ($i=0; $i < count($row); $i++) {
                $this
                    ->setWidth(count($row) === $i+1 ? 0 : $this->rowSize[$i])
                    ->setText($row[$i])
                    ->setAlign(count($this->rowAlign) === 0 ? '' : $this->rowAlign[$i])
                    ->setFill(true)
                    ->setBackgroundColor($background);
                    // ->setBorderColor($dark_blue)
                    // ->setColor($dark_blue)
                    parent::draw();
            }

            $this->raw->Ln();
        }
        return $this;
    }

    public function clear() {
        parent::clear();
        return $this;
    }
}