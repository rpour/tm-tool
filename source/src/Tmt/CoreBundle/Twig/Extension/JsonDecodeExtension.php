<?php

namespace Tmt\CoreBundle\Twig\Extension;

class JsonDecodeExtension extends \Twig_Extension {
    public function getFilters() {
        return array(
            'json_decode' => new \Twig_Filter_Method($this, 'jsonDecode', array('is_safe' => array('html')))
        );
    }

    public function getName() {
        return 'debug_extension';
    }


    public function jsonDecode($var) {
        return json_decode($var, true);
    }
}
