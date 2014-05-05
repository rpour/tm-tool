<?php

namespace Tmt\ProjectBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Tmt\CoreBundle\Component\EntityManipulation;

class Project extends EntityManipulation {
    protected $repository = 'TmtProjectBundle:Project';

    public function __construct(Container $container) {
        parent::__construct($container);
    }
}