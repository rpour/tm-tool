<?php

namespace Tmt\CoreBundle\Component\PDF;

class PDF extends Icon
{
    public $raw;
    protected $fontFamily = 'Helvetica';

    public function __construct($raw)
    {
        parent::__construct();
        $this->raw = $raw;
    }

    public function newLine($h = '')
    {
        $this->raw->Ln($h);
        return $this;
    }

    public function bold()
    {
        $this->raw->SetFont('', 'B');
        return $this;
    }

    public function setFontSize($size)
    {
        $this->raw->SetFontSize($size);
        return $this;
    }

    public function setColor($color)
    {
        list($red, $green, $blue) = $this->parseHtmlColor($color);
        $this->raw->SetTextColor($red, $green, $blue);
        return $this;
    }

    public function setBackgroundColor($color)
    {
        list($red, $green, $blue) = $this->parseHtmlColor($color);
        $this->raw->SetFillColor($red, $green, $blue);
        return $this;
    }

    public function setBorderColor($color)
    {
        list($red, $green, $blue) = $this->parseHtmlColor($color);
        $this->raw->SetDrawColor($red, $green, $blue);
        return $this;
    }

    public function forceDownload($filename = null)
    {
        if (is_null($filename)) {
            $filename = 'temp.pdf';
        }

        $this->raw->Output($filename, 'D');
    }

    private function parseHtmlColor($color)
    {
        return array(
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
    }

    protected function clear()
    {
        $this->setColor('000000');
        $this->setBackgroundColor('ffffff');
        $this->setBorderColor('000000');
        $this->raw->SetFont('', '');
    }
}
