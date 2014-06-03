<?php

namespace Tmt\CoreBundle\Component\PDF;

class Icon
{
    private $iconData = array();
    protected $iconFont = "";

    public function __construct()
    {
        $this->iconData = array(
// data#start
'icon-tux' => 'e600',
'icon-apple' => 'e601',
'icon-windows' => 'e602',
'icon-windows8' => 'e603',
'icon-chrome' => 'e604',
'icon-firefox' => 'e605',
'icon-IE' => 'e606',
'icon-opera' => 'e607',
'icon-safari' => 'e608',
'icon-user' => 'f007',
'icon-check' => 'f00c',
'icon-times' => 'f00d',
'icon-power-off' => 'f011',
'icon-trash-o' => 'f014',
'icon-home' => 'f015',
'icon-file-o' => 'f016',
'icon-clock-o' => 'f017',
'icon-book' => 'f02d',
'icon-list' => 'f03a',
'icon-edit' => 'f044',
'icon-warning' => 'f071',
'icon-calendar' => 'f073',
'icon-key' => 'f084',
'icon-sign-in' => 'f090',
'icon-plus-square' => 'f0fe',
'icon-mail-reply' => 'f112',
'icon-minus-square' => 'f146',
// data#end
        );
    }

    public function setFontFile($file)
    {
        $this->iconFont = @$this->raw->addTTFfont($file, 'TrueTypeUnicode', '', 32);
        return $this;
    }

    public function getIcon($name)
    {
        return isset($this->iconData[trim($name)])
            ? mb_convert_encoding('&#x' . strtoupper($this->iconData[trim($name)]) . ';', 'UTF-8', 'HTML-ENTITIES')
            : '';
    }
}
