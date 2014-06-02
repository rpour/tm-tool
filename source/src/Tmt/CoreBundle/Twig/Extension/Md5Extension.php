<?php

namespace Tmt\CoreBundle\Twig\Extension;

class Md5Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'md5' => new \Twig_Filter_Method($this, 'md5')
        );
    }

    public function getName()
    {
        return 'md5_extension';
    }


    public function md5($var)
    {
        return md5($var);
    }
}
