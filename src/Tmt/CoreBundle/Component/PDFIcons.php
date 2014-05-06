<?php

namespace Tmt\CoreBundle\Component;

class PDFIcons {
    private $data = array();

    public function __construct() {
        $this->data = array(
// data#start
'icon-user' => 'f007',
'icon-check' => 'f00c',
'icon-times' => 'f00d',
'icon-power-off' => 'f011',
'icon-trash-o' => 'f014',
'icon-home' => 'f015',
'icon-file-o' => 'f016',
'icon-book' => 'f02d',
'icon-list' => 'f03a',
'icon-edit' => 'f044',
'icon-warning' => 'f071',
'icon-key' => 'f084',
'icon-sign-in' => 'f090',
'icon-plus-square' => 'f0fe',
'icon-mail-reply' => 'f112',
'icon-minus-square' => 'f146',
//data#end
        );
    }

    public function get($name) {
        return isset($this->data[trim($name)])
            ? mb_convert_encoding('&#x' . strtoupper($this->data[trim($name)]) . ';', 'UTF-8', 'HTML-ENTITIES')
            : '';
    }
}