<?php

namespace Tmt\ProjectBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class Project {
    private $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
}