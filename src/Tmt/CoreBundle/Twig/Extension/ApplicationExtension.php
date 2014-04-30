<?php

namespace Tmt\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApplicationExtension extends \Twig_Extension {
    protected $container;

    public function __construct(ContainerInterface $container) {
        if ($container->isScopeActive('request'))
            $this->container = $container;
    }

    public function getFunctions() {
        return array(
            'applicationGet' => new \Twig_Function_Method($this, 'applicationGet')
        );
    }

    public function getName() {
        return 'application_extension';
    }

    public function applicationGet($parameter) {
        $application = $this->container->get('tmt.application');
        return $application->get($parameter);
    }
}