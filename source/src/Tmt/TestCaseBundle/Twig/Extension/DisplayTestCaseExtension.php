<?php

namespace Tmt\TestCaseBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DisplayTestCaseExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        if ($container->isScopeActive('request')) {
            $this->container = $container;
        }
    }

    public function getFilters()
    {
        return array(
            'display_testcase' => new \Twig_Filter_Method($this, 'displayTestCase', array('is_safe' => array('html')))
        );
    }

    public function getName()
    {
        return 'display_test_case_extension';
    }

    public function displayTestCase($string)
    {
        preg_match_all("/(http:\/\/[^ \n\r]+)/i", $string, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $url) {
                $string = str_replace($url, "<a href=\"$url\" target=\"_BLANK\">$url</a>", $string);
            }
        }

        $string = str_replace("\n", "<br/>", $string);

        return $string;
    }
}
