<?php

namespace Tmt\CoreBundle\Component\PDF;

/**
 * http://www.tcpdf.org/doc/code/classTCPDF.html#a33b265e5eb3e4d1d4fedfe29f8166f31
 */
class Cell extends PDF
{
    protected $width;
    protected $height;
    protected $text;
    protected $border;
    protected $ln;
    protected $align;
    protected $fill;
    protected $link;
    protected $stretch;
    protected $ignoreMinHeight;
    protected $calign;
    protected $valign;
    protected $isIcon = false;

    public function __construct($pdf)
    {
        parent::__construct($pdf);
        $this->clear();
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function setText($text)
    {
        if (strtolower(substr($text, 0, 8)) === '::icon::') {
            $text = $this->getIcon(substr($text, 8));
            $this->isIcon = true;
        }
        $this->text = $text;
        return $this;
    }

    public function setBorder($border)
    {
        $this->border = $border;
        return $this;
    }

    public function setLn($ln)
    {
        $this->ln = $ln;
        return $this;
    }

    public function setFill($fill)
    {
        $this->fill = $fill;
        return $this;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function setStretch($stretch)
    {
        $this->stretch = $stretch;
        return $this;
    }

    public function setIgnoreMinHeight($ignoreMinHeight)
    {
        $this->ignoreMinHeight = $ignoreMinHeight;
        return $this;
    }

    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    public function setCalign($calign)
    {
        $this->calign = $calign;
        return $this;
    }

    public function setValign($valign)
    {
        $this->valign = $valign;
        return $this;
    }

    public function draw()
    {
        if ($this->isIcon) {
            $this->raw->SetFont($this->iconFont, '', null, '', false);
        }

        $this->raw->Cell(
            $this->width,
            $this->height,
            $this->text,
            $this->border,
            $this->ln,
            $this->align,
            $this->fill,
            $this->link,
            $this->stretch,
            $this->ignoreMinHeight,
            $this->calign,
            $this->valign
        );

        if ($this->isIcon) {
            $this->isIcon = false;
            $this->raw->SetFont($this->fontFamily);
        }

        return $this;
    }

    public function clear()
    {
        parent::clear();

        $this->width           = '';
        $this->height          = 0;
        $this->text            = '';
        $this->border          = 0;
        $this->ln              = 0;
        $this->align           = '';
        $this->fill            = true;
        $this->link            = '';
        $this->stretch         = 0;
        $this->ignoreMinHeight = false;
        $this->calign          = 'T';
        $this->valign          = 'M';
        return $this;
    }
}
