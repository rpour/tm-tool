<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TestCase {
    private $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
}