<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\TestCaseBundle\Entity\UserAgent as UserAgentEntity;

class UserAgent extends EntityManipulation
{
    protected $repository = 'TmtTestCaseBundle:UserAgent';


    public function __construct(Container $container)
    {
        parent::__construct($container);
    }
}
